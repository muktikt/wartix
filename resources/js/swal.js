import Swal from 'sweetalert2';

/**
 * Modern SweetAlert2 instance configured for Wartix design system.
 */
export const WartixSwal = Swal.mixin({
    customClass: {
        popup: 'wartix-swal-popup',
        title: 'wartix-swal-title',
        htmlContainer: 'wartix-swal-html',
        confirmButton: 'wartix-swal-confirm-primary',
        cancelButton: 'wartix-swal-cancel',
        actions: 'flex items-center justify-end gap-2.5 mt-6',
    },
    buttonsStyling: false,
    focusCancel: true,
    showClass: {
        popup: 'animate-scale-in duration-200 ease-out',
    },
    hideClass: {
        popup: 'animate-fade-out duration-150 ease-in',
    }
});

/**
 * Helper to show sleek confirmation popups (e.g. Delete, Action confirms)
 */
export function showConfirmPopup({
    title = 'Konfirmasi Tindakan',
    text = 'Apakah Anda yakin ingin melanjutkan?',
    confirmText = 'Ya, Lanjutkan',
    cancelText = 'Batal',
    icon = 'warning',
    isDanger = true
} = {}) {
    const confirmClass = isDanger ? 'wartix-swal-confirm-danger' : 'wartix-swal-confirm-primary';

    return WartixSwal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true,
        customClass: {
            popup: 'wartix-swal-popup',
            title: 'wartix-swal-title',
            htmlContainer: 'wartix-swal-html',
            confirmButton: confirmClass,
            cancelButton: cancelButtonText ? 'wartix-swal-cancel' : 'hidden',
            actions: 'flex items-center justify-end gap-2.5 mt-6',
        }
    });
}

/**
 * Helper to show toast notifications
 */
export function showToast(message, icon = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        customClass: {
            popup: 'rounded-2xl shadow-xl border border-gray-100 p-4 font-sans text-sm bg-white text-gray-800 flex items-center gap-3',
            title: 'font-medium text-gray-900 text-sm'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    return Toast.fire({
        icon: icon,
        title: message
    });
}

/**
 * Initialize global event interceptors for all confirm popups across the app.
 * Automatically catches forms with `onsubmit="return confirm(...)"`, `data-confirm="..."`, or delete buttons.
 */
export function initGlobalConfirmInterceptors() {
    // Intercept form submissions
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!form) return;

        // If form has already been confirmed by user, let it submit normally
        if (form.dataset.confirmed === 'true') {
            return;
        }

        const onsubmitAttr = form.getAttribute('onsubmit') || '';
        const dataConfirm = form.getAttribute('data-confirm');

        let confirmMsg = dataConfirm;

        if (!confirmMsg && onsubmitAttr.includes('confirm(')) {
            const match = onsubmitAttr.match(/confirm\((['"])(.*?)\1\)/);
            if (match && match[2]) {
                confirmMsg = match[2];
            }
        }

        if (confirmMsg) {
            e.preventDefault();
            e.stopPropagation();

            const isDelete = confirmMsg.toLowerCase().includes('hapus') || 
                             confirmMsg.toLowerCase().includes('delete') ||
                             Boolean(form.querySelector('input[name="_method"][value="DELETE"]'));

            const title = isDelete ? 'Konfirmasi Hapus' : 'Konfirmasi Action';
            const confirmBtnText = isDelete ? 'Ya, Hapus' : 'Ya, Lanjutkan';

            showConfirmPopup({
                title: title,
                text: confirmMsg,
                confirmText: confirmBtnText,
                cancelText: 'Batal',
                icon: isDelete ? 'warning' : 'question',
                isDanger: isDelete
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }
            });
        }
    }, true);

    // Intercept standalone links or non-form elements with onclick="return confirm('...')" or data-confirm
    document.addEventListener('click', function (e) {
        const clickable = e.target.closest('a[data-confirm], a[onclick*="confirm("], button[onclick*="confirm("]');
        if (!clickable) return;

        // If it's a submit button inside a form, the form submit listener above will handle it.
        if (clickable.tagName === 'BUTTON' && clickable.closest('form')) {
            return;
        }

        const onclickAttr = clickable.getAttribute('onclick') || '';
        const dataConfirm = clickable.getAttribute('data-confirm');

        let confirmMsg = dataConfirm;
        if (!confirmMsg && onclickAttr.includes('confirm(')) {
            const match = onclickAttr.match(/confirm\((['"])(.*?)\1\)/);
            if (match && match[2]) {
                confirmMsg = match[2];
            }
        }

        if (confirmMsg) {
            e.preventDefault();
            e.stopPropagation();

            const isDelete = confirmMsg.toLowerCase().includes('hapus') || confirmMsg.toLowerCase().includes('delete');

            showConfirmPopup({
                title: isDelete ? 'Konfirmasi Hapus' : 'Konfirmasi Action',
                text: confirmMsg,
                confirmText: isDelete ? 'Ya, Hapus' : 'Ya, Lanjutkan',
                cancelText: 'Batal',
                icon: isDelete ? 'warning' : 'question',
                isDanger: isDelete
            }).then((result) => {
                if (result.isConfirmed) {
                    if (clickable.tagName === 'A' && clickable.href) {
                        window.location.href = clickable.href;
                    }
                }
            });
        }
    }, true);
}

// Global binding
window.Swal = Swal;
window.WartixSwal = WartixSwal;
window.showConfirmPopup = showConfirmPopup;
window.showToast = showToast;
