// تنظيف النموذج
function clearForm() {
    document.getElementById('first_name').value = '';
    document.getElementById('last_name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
    document.getElementById('phone_number').value = '';
    document.getElementById('birth_of_date').value = '';
    document.getElementById('address').value = '';
    document.getElementById('role').value = 'user';
}

// إضافة مستخدم جديد
document.getElementById('userForm').addEventListener('submit', function (e) {
    e.preventDefault(); // منع إعادة تحميل الصفحة

    const formData = new FormData(this); // جمع بيانات النموذج

    fetch('add_user.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // إظهار SweetAlert عند نجاح الإضافة
                Swal.fire({
                    title: 'Success!',
                    text: 'User added successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#addUserModal').modal('hide');
                    location.reload(); // (اختياري) إعادة تحميل الصفحة لتحديث البيانات
                });
            } else {
                // عرض الأخطاء داخل SweetAlert
                let errorMessages = data.errors.join('<br>');
                Swal.fire({
                    title: 'Error!',
                    html: errorMessages,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred. Please try again later.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
});

// تعديل مستخدم
$(document).ready(function () {
    // عند إرسال نموذج التعديل
    $(document).on('submit', '.editUserForm', function (e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: 'update_user.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                console.log(response); // تحقق من الاستجابة هنا

                if (response.success) {
                    // إظهار SweetAlert عند نجاح التحديث
                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // تأخير إعادة تحميل الصفحة بعد الضغط على OK
                            setTimeout(() => {
                                location.reload();
                            }, 500); // تأخير إعادة التحميل لمدة نصف ثانية (500ms)
                        }
                    });
                } else {
                    // عرض الأخطاء داخل SweetAlert
                    let errorMessages = response.errors.join('<br>');
                    Swal.fire({
                        title: 'Error!',
                        html: errorMessages,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred. Please try again later.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

// حذف مستخدم
function confirmDelete(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_user.php',
                method: 'GET',
                data: { user_id: userId },
                dataType: 'json',
                success: function (response) {
                    Swal.fire(
                        'Deleted!',
                        'The user has been deleted.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function () {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the user.',
                        'error'
                    );
                }
            });
        }
    });
}
