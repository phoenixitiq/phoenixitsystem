function sendEmail(email) {
    // عرض نافذة منبثقة لكتابة رسالة مخصصة
    Swal.fire({
        title: 'إرسال بريد إلكتروني',
        html: `
            <textarea id="emailMessage" class="form-control" 
                      rows="5" placeholder="اكتب رسالتك هنا..."></textarea>
        `,
        showCancelButton: true,
        confirmButtonText: 'إرسال',
        cancelButtonText: 'إلغاء',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const message = document.getElementById('emailMessage').value;
            return fetch('/admin/send-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email, message })
            }).then(response => response.json());
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('تم!', 'تم إرسال البريد الإلكتروني بنجاح', 'success');
        }
    });
} 