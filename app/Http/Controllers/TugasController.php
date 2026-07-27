<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasDetail;
use App\Models\User;
use App\Models\Lampiran;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    /* =========================
       HELPER: AUTO TERLAMBAT
    ========================= */
    private function checkTerlambat($tugas)
    {
        if (
            $tugas->status !== 'Selesai' &&
            $tugas->status !== 'Terlambat' &&
            $tugas->deadline &&
            now()->gt($tugas->deadline)
        ) {
            $tugas->status = 'Terlambat';
            $tugas->save();
        }
    }
    

    /* =========================
       CREATE TUGAS
    ========================= */
    public function store(Request $request)
    {
        $tugas = Tugas::create([
            'user_id' => $request->user_id,
            'bagian' => $request->bagian,
            'kategori' => $request->kategori,
            'keterangan_laporan' => $request->keterangan_laporan,
            'solusi' => $request->solusi,
            'status' => 'Penugasan',
            'deadline' => $request->deadline
        ]);

        return response()->json($tugas);
    }

    /* =========================
       GET ALL
    ========================= */
    public function index()
    {
        $tugas = Tugas::with('detail')->latest()->get();

        foreach ($tugas as $t) {

            // cek terlambat (punyamu tetap dipakai)
            $this->checkTerlambat($t);

            // 🔴 TAMBAHAN: cek ada yang menunggu persetujuan
            $t->ada_menunggu = $t->detail->contains(function ($d) {
                return $d->status === 'Menunggu Persetujuan';
            });

        }

        return $tugas;
    }

    /* =========================
       GET BY USER
    ========================= */
    public function userTasks($id)
    {
        $tugas = User::findOrFail($id)
            ->tugas()
            ->latest()
            ->get();

        foreach ($tugas as $t) {
            $this->checkTerlambat($t);
        }

        return $tugas;
    }

    /* =========================
       SHOW DETAIL
    ========================= */
    public function show($id)
    {
        $t = Tugas::findOrFail($id);
        $this->checkTerlambat($t);
        return $t;
    }

    /* =========================
       UPDATE
    ========================= */
    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        $tugas->update([
            'kategori' => $request->kategori,
            'bagian' => $request->bagian,
            'keterangan_laporan' => $request->keterangan_laporan,
            'deadline' => $request->deadline,
        ]);

        return response()->json($tugas);
    }

    /* =========================
       DETAIL LIST (SUDAH ADA USER)
    ========================= */
    public function detail($id)
    {
        return TugasDetail::with('user')
            ->where('tugas_id', $id)
            ->get();
    }

    /* =========================
       SIMPAN DETAIL
    ========================= */
    public function storeDetail(Request $request, $id)
    {
        foreach ($request->details as $item) {


            if(isset($item['id'])) {


                TugasDetail::where('id',$item['id'])
                ->update([
                    'deskripsi'=>$item['deskripsi'],
                    'tanggal'=>$item['tanggal'] ?? null,
                ]);


            } else {


                TugasDetail::create([
                    'tugas_id'=>$id,
                    'deskripsi'=>$item['deskripsi'],
                    'status'=>'Progres',
                    'tanggal'=>$item['tanggal'] ?? null,
                    'user_id'=>$request->user_id,
                ]);

            }

        }


        $total = TugasDetail::where('tugas_id',$id)->count();

        $selesai = TugasDetail::where('tugas_id',$id)
            ->where('status','Selesai')
            ->count();


        $progres = TugasDetail::where('tugas_id',$id)
            ->where('status','Progres')
            ->count();


        $menunggu = TugasDetail::where('tugas_id',$id)
            ->where('status','Menunggu Persetujuan')
            ->count();


        $tugas = Tugas::findOrFail($id);


        if($total == 0){

            $tugas->status='Penugasan';

        }elseif($selesai == $total){

            $tugas->status='Selesai';

        }elseif($progres > 0 || $menunggu > 0){

            $tugas->status='Progres';

        }else{

            $tugas->status='Penugasan';

        }


        $this->checkTerlambat($tugas);

        $tugas->save();


        return response()->json([
            'message'=>'Detail tersimpan',
            'status'=>$tugas->status
        ]);
    }

    /* =========================
       DELETE DETAIL
    ========================= */
    public function deleteDetail($id)
    {
        TugasDetail::find($id)?->delete();

        return response()->json(['message' => 'Detail dihapus']);
    }

    /* =========================
       PROGRESS %
    ========================= */
    public function progress($id)
    {
        $total = TugasDetail::where('tugas_id', $id)->count();
        $selesai = TugasDetail::where('tugas_id', $id)->where('status', 'Selesai')->count();

        $progress = $total > 0
            ? round(($selesai / $total) * 100)
            : 0;

        return response()->json(['progress' => $progress]);
    }

    /* =========================
       ASSIGN
    ========================= */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $tugas = Tugas::findOrFail($id);
        $tugas->users()->syncWithoutDetaching([$request->user_id]);

        return response()->json([
            'message' => 'Tugas berhasil ditugaskan'
        ]);
    }

    /* =========================
       GET ASSIGNED USERS
    ========================= */
    public function users($id)
    {
        return Tugas::with('users')
            ->findOrFail($id)
            ->users;
    }

    /* =========================
       DISKUSI LIST
    ========================= */
    public function getDiskusi($id)
    {
        return \App\Models\DiskusiTugas::with('user')
            ->where('tugas_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /* =========================
       KIRIM DISKUSI (AMAN)
    ========================= */
   public function storeDiskusi(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pesan' => 'required|string'
        ]);


        $diskusi = \App\Models\DiskusiTugas::create([
            'tugas_id' => $id,
            'user_id' => $request->user_id,
            'pesan' => $request->pesan
        ]);


        return response()->json([
            'message'=>'Diskusi berhasil dikirim',
            'data'=>$diskusi->load('user')
        ]);
    }

    public function updateDetail(Request $request, $id)
    {
        $detail = TugasDetail::findOrFail($id);

        $user = auth()->user();


        // karyawan hanya boleh edit miliknya sendiri
        if ($user->role === 'karyawan' && $detail->user_id != $user->id) {

            return response()->json([
                'message' => 'Anda tidak memiliki akses mengubah rincian ini'
            ], 403);

        }


        $detail->update([
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
        ]);

        return response()->json([
            'message'=>'Rincian berhasil diperbarui'
        ]);
    }

    public function ajukanSelesai(Request $request, $id)
    {
        $detail = TugasDetail::findOrFail($id);


        $user = User::find($request->user_id);


        if (!$user) {
            return response()->json([
                'message'=>'User tidak ditemukan'
            ],403);
        }


        if ($user->role !== 'karyawan') {
            return response()->json([
                'message'=>'Hanya karyawan yang dapat mengajukan selesai'
            ],403);
        }


        if ($detail->user_id != $user->id) {

            return response()->json([
                'message'=>'Anda bukan pemilik rincian tugas ini'
            ],403);

        }


        $detail->update([
            'status'=>'Menunggu Persetujuan'
        ]);


        return response()->json([
            'message'=>'Menunggu persetujuan atasan'
        ]);
    }
    
    public function setujuiSelesai(Request $request, $id)
    {
        $user = User::find($request->user_id);


        if(!$user || $user->role !== 'atasan'){

            return response()->json([
                'message'=>'Hanya atasan yang dapat menyetujui'
            ],403);

        }


        $detail = TugasDetail::findOrFail($id);


        $detail->update([
            'status'=>'Selesai'
        ]);


        // CEK STATUS TUGAS UTAMA
        $total = TugasDetail::where('tugas_id',$detail->tugas_id)
            ->count();

        $selesai = TugasDetail::where('tugas_id',$detail->tugas_id)
            ->where('status','Selesai')
            ->count();


        $tugas = Tugas::find($detail->tugas_id);


        if($total > 0 && $selesai == $total){

            $tugas->status='Selesai';

        }else{

            $tugas->status='Progres';

        }


        $tugas->save();


        return response()->json([
            'message'=>'Tugas berhasil diselesaikan',
            'status'=>$tugas->status
        ]);
    }

    public function uploadLampiran(Request $request, $id)
    {
        Tugas::findOrFail($id);

        $request->validate([
            'file' => 'required|file|max:5120',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $file = $request->file('file');
        $path = $file->store('lampiran', 'public');

        $lampiran = Lampiran::create([
            'tugas_id' => $id,
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => $path,
            'keterangan' => $request->keterangan
        ]);

        return response()->json([
            'message' => 'Upload berhasil',
            'data' => $lampiran
        ]);
    }

    public function getLampiran($id)
    {
        return Lampiran::where('tugas_id', $id)
            ->latest()
            ->get();
    }

    public function downloadLampiran($id)
    {
        $lampiran = Lampiran::findOrFail($id);

        if (!Storage::disk('public')->exists($lampiran->file_path)) {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }

        return Storage::disk('public')->download(
            $lampiran->file_path,
            $lampiran->nama_file
        );
    }

    public function deleteLampiran($id)
    {
        $lampiran = Lampiran::findOrFail($id);

        if ($lampiran->file_path) {
            Storage::disk('public')->delete($lampiran->file_path);
        }

        $lampiran->delete();

        return response()->json([
            'message' => 'Berhasil dihapus'
        ]);
    }

    /* =========================
    DASHBOARD KINERJA
    ========================= */
    public function dashboardKinerja()
    {
        $totalTugas = Tugas::count();

        $tugasSelesai = Tugas::where('status', 'Selesai')
            ->count();

        $tugasProses = Tugas::where('status', 'Progres')
            ->count();

        $keterlambatan = Tugas::where('status', 'Terlambat')
            ->count();


        return response()->json([
            'totalTugas' => $totalTugas,
            'tugasSelesai' => $tugasSelesai,
            'tugasProses' => $tugasProses,
            'keterlambatan' => $keterlambatan
        ]);
    }



    /* =========================
    PERFORMA KARYAWAN
    ========================= */
    public function performaKaryawan()
    {
        $karyawan = User::where('role', 'karyawan')
            ->get();


        $totalSemuaTugas = Tugas::count();


        $data = $karyawan->map(function ($user) use ($totalSemuaTugas) {


            // jumlah tugas yang diberikan ke user
            $tugasUser = Tugas::whereHas('users', function($q) use ($user){

                $q->where('users.id', $user->id);

            });


            $totalTugas = (clone $tugasUser)->count();


            $tugasSelesai = (clone $tugasUser)
                ->where('status','Selesai')
                ->count();


            $tugasTerlambat = (clone $tugasUser)
                ->where('status','Terlambat')
                ->count();



            // PERFORMANCE INDIVIDU
            $performance = $totalTugas > 0
                ? round(($tugasSelesai / $totalTugas) * 100)
                : 0;



            // CONTRIBUTION TERHADAP SEMUA TUGAS
            $contribution = $totalSemuaTugas > 0
                ? round(($tugasSelesai / $totalSemuaTugas) * 100)
                : 0;



            // NILAI AKHIR
            $skor = round(
                ($performance * 0.7) +
                ($contribution * 0.3)
            );



            return [

                'id' => $user->id,

                'name' => $user->name,

                'bagian' => $user->bagian,


                'totalTugas' => $totalTugas,

                'tugasSelesai' => $tugasSelesai,

                'tugasTerlambat' => $tugasTerlambat,


                'performance' => $performance,

                'contribution' => $contribution,

                'skor' => $skor

            ];

        });


        return response()->json($data);
    }

    public function detailKinerjaKaryawan($id)
    {
        $user = User::with([
            'tugas' => function($q) use ($id){

                $q->with([
                    'detail' => function($detail) use ($id){

                        $detail->where('user_id',$id);

                    }
                ]);

            }
        ])
        ->findOrFail($id);


        return response()->json([

            'nama'=>$user->name,

            'bagian'=>$user->bagian,

            'tugas'=>$user->tugas

        ]);
    }

}
