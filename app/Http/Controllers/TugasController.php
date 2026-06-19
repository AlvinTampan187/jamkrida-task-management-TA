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
       🔥 HELPER: AUTO TERLAMBAT
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
        $tugas = Tugas::latest()->get();

        foreach ($tugas as $t) {
            $this->checkTerlambat($t);
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
       UPDATE (TANPA STATUS)
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
       DETAIL LIST
    ========================= */
    public function detail($id)
    {
        return TugasDetail::where('tugas_id', $id)->get();
    }


    /* =========================
       SIMPAN DETAIL + AUTO STATUS
    ========================= */
    public function storeDetail(Request $request, $id)
    {
        TugasDetail::where('tugas_id', $id)->delete();

        foreach ($request->details as $item) {

            $status = trim($item['status'] ?? 'Penugasan');
            $status = ucfirst(strtolower($status));

            if (!in_array($status, ['Penugasan','Progres','Selesai'])) {
                $status = 'Penugasan';
            }

            TugasDetail::create([
                'tugas_id' => $id,
                'deskripsi' => $item['deskripsi'],
                'status' => $status,
                'tanggal' => $item['tanggal'] ?? null
            ]);
        }

        // 🔥 HITUNG STATUS
        $total = TugasDetail::where('tugas_id', $id)->count();

        $selesai = TugasDetail::where('tugas_id', $id)
            ->where('status', 'Selesai')
            ->count();

        $progres = TugasDetail::where('tugas_id', $id)
            ->where('status', 'Progres')
            ->count();

        $tugas = Tugas::findOrFail($id);

        if ($total == 0) {
            $tugas->status = 'Penugasan';
        } elseif ($selesai == $total) {
            $tugas->status = 'Selesai';
        } elseif ($progres > 0 || $selesai > 0) {
            $tugas->status = 'Progres';
        } else {
            $tugas->status = 'Penugasan';
        }

        // 🔥 CEK TERLAMBAT SETELAH UPDATE
        $this->checkTerlambat($tugas);

        $tugas->save();

        return response()->json([
            'message' => 'Detail tersimpan',
            'status' => $tugas->status
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

        $selesai = TugasDetail::where('tugas_id', $id)
            ->where('status', 'Selesai')
            ->count();

        $progress = $total > 0
            ? round(($selesai / $total) * 100)
            : 0;

        return response()->json([
            'progress' => $progress
        ]);
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
       UNASSIGN
    ========================= */
    public function unassign($id, $userId)
    {
        Tugas::findOrFail($id)
            ->users()
            ->detach($userId);

        return response()->json([
            'message' => 'Karyawan dilepas'
        ]);
    }


    /* =========================
       UPLOAD FILE
    ========================= */
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


    /* =========================
       GET FILE
    ========================= */
    public function getLampiran($id)
    {
        return Lampiran::where('tugas_id', $id)
            ->latest()
            ->get();
    }


    /* =========================
       DOWNLOAD FILE
    ========================= */
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


    /* =========================
       DELETE FILE
    ========================= */
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
       HALAMAN KINERJA
    ========================= */
    public function dashboardKinerja()
    {
        $totalTugas = Tugas::count();
        $tugasSelesai = Tugas::where('status', 'Selesai')->count();
        $keterlambatan = Tugas::where('status', 'Terlambat')->count();
        $tugasProses = Tugas::where('status', 'Progres')->count();
        $tugasPending = Tugas::where('status', 'Penugasan')->count();

        return response()->json([
            'totalTugas' => $totalTugas,
            'tugasSelesai' => $tugasSelesai,
            'keterlambatan' => $keterlambatan,
            'tugasProses' => $tugasProses,
            'tugasPending' => $tugasPending,
            'rataPenyelesaian' => 24 // sementara dummy
        ]);
    }

    public function performaKaryawan()
    {
        $users = User::with('tugas')
            ->where('role', 'karyawan')
            ->get();

        // total semua tugas di sistem
        $totalSemuaTugas = Tugas::count();

        $data = $users->map(function ($user) use ($totalSemuaTugas) {

            $total = $user->tugas->count();

            $selesai = $user->tugas
                ->where('status', 'Selesai')
                ->count();

            $terlambat = $user->tugas
                ->filter(function ($t) {
                    return $t->status !== 'Selesai'
                        && $t->deadline < now();
                })
                ->count();

            // ===============================
            // 1. PERFORMANCE (INDIVIDU)
            // ===============================
            $performance = $total > 0
                ? round(($selesai / $total) * 100)
                : 0;

            // ===============================
            // 2. CONTRIBUTION (GLOBAL)
            // ===============================
            $contribution = $totalSemuaTugas > 0
                ? round(($selesai / $totalSemuaTugas) * 100)
                : 0;

            // ===============================
            // 3. FINAL SCORE (70% + 30%)
            // ===============================
            $finalScore = round(
                min(100, ($performance * 0.7) + ($contribution * 0.3))
            );

            return [
                'id' => $user->id,
                'name' => $user->name,
                'bagian' => $user->bagian,
                'totalTugas' => $total,
                'tugasSelesai' => $selesai,
                'tugasTerlambat' => $terlambat,
                'performance' => $performance,
                'contribution' => $contribution,
                'skor' => $finalScore
            ];
        });

        // 🔥 SORT BERDASARKAN SKOR TERBAIK
        return response()->json(
            $data->sortByDesc('skor')->values()
        );
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
    KIRIM DISKUSI
    ========================= */
    public function storeDiskusi(Request $request, $id)
    {
        if (!$request->user_id) {
            return response()->json([
                'error' => 'user_id kosong'
            ], 400);
        }

        $diskusi = \App\Models\DiskusiTugas::create([
            'tugas_id' => $id,
            'user_id' => $request->user_id,
            'pesan' => $request->pesan
        ]);

        return response()->json($diskusi);
    }
}