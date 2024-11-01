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

document.getElementById('userForm').addEventListener('submit', function (e) {
    e.preventDefault();// Prevent page reloading

    const formData = new FormData(this); 

    fetch('add_user.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            // Show SweetAlert when the add is successful
                Swal.fire({
                    title: 'Success!',
                    text: 'User added successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#addUserModal').modal('hide');
                    location.reload();// (Optional) Reload the page to update the data.
                });
            } else {

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

$(document).ready(function () {

    $(document).on('submit', '.editUserForm', function (e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: 'update_user.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                console.log(response); 

                if (response.success) {
                
                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                         
                            setTimeout(() => {
                                location.reload();
                            }, 500); 
                        }
                    });
                } else {
                  
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
