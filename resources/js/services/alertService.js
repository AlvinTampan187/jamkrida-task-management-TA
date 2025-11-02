import Swal from 'sweetalert2'

const buttonColorPrimary = 'oklch(60% 0.118 184.704)'
const buttonColorSecondary = 'oklch(63.7% 0.237 25.331)'

export const getSuccess = async (text = 'Berhasil !') => {
    await Swal.fire({
        icon: 'success',
        text,
        confirmButtonColor: buttonColorPrimary
    })
}

export async function getSuccessWithTitle(title = 'Berhasil !', text = 'Berhasil !') {
    await Swal.fire({
        icon: 'success',
        title,
        text,
        confirmButtonColor: buttonColorPrimary
    })
}

export const getError = async (text = 'Error !') => {
    await Swal.fire({
        icon: 'error',
        text,
        confirmButtonColor: buttonColorPrimary
    })
}
export const getInfo = async (text = 'Informasi !') => {
    await Swal.fire({
        icon: 'info',
        text,
        confirmButtonColor: buttonColorPrimary
    })
}
export const getWarning = async (text = 'Peringatan !') => {
    await Swal.fire({
        icon: 'warning',
        text,
        confirmButtonColor: buttonColorPrimary
    })
}

export const getConfirm = async (text = 'Yakin?') => {
    const response = await Swal.fire({
        icon: 'question',
        text,
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonColor: buttonColorPrimary,
        cancelButtonColor: buttonColorSecondary,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak'
    })

    return response
}
