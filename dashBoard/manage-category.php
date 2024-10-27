<?php
// تضمين الملفات الضرورية
include_once 'config.php';
include_once 'Category.php';

// إنشاء كائن فئة
$category = new Category($pdo);

// جلب جميع الفئات غير المحذوفة
$categories = $category->getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Categories - Scentify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="Css.css">
    <style>
                .search-bar-wrapper {
    max-width: 400px; /* أقصى عرض لشريط البحث */
}

.search-bar {
    background-color: white;
    width: 250px; /* عرض كامل للعنصر */
    padding: 10px 20px; /* حشوة داخلية */
    border: none; /* إزالة الحدود */
    border-radius: 50px; /* زوايا دائرية */
    outline: none; /* إزالة الخط الخارجي عند التركيز */
    font-size: 16px; /* حجم الخط */
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2); /* ظل خفيف */
    transition: all 0.3s ease; /* تأثيرات انتقالية */
}

.search-bar:focus {
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2); /* ظل خفيف */
}
    </style>
</head>

<body>
    <?php include "header.php" ?>
    <div class="container-fluid page-body-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-users.php">
                        <i class="icon-head menu-icon"></i>
                        <span class="menu-title">Manage Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-orders.php">
                        <i class="icon-cart menu-icon"></i>
                        <span class="menu-title">Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-products.php">
                        <i class="icon-box menu-icon"></i>
                        <span class="menu-title">Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-category.php">
                        <i class="icon-tag menu-icon"></i>
                        <span class="menu-title">Category</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-coupons.php">
                        <i class="icon-tag menu-icon"></i>
                        <span class="menu-title">Coupons</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="container-fluid content-background">
        <div class="content-wrapper">
        <h2 class="mt-4">Manage Categories</h2>
        <div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#categoryModal" onclick="clearForm()">Add New Category</button>
            <div class="search-bar-wrapper">
                <input type="text" class="search-bar form-control" placeholder="Search Category">
            </div>
        </div>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $num = 1;
                    foreach ($categories as $cat) {
                        $id = $cat['category_id'];
                        $name = htmlspecialchars($cat['category_name']);
                        $created_at = htmlspecialchars($cat['created_at']);
                        $updated_at = htmlspecialchars($cat['updated_at']);
                        $image = htmlspecialchars($cat['image']);

                        echo "<tr>
                        <td>{$num}</td>
                        <td>{$name}</td>
                        <td>{$created_at}</td>
                        <td>{$updated_at}</td>
                        <td><img src='{$image}' alt='Category Image' class='img-thumbnail'></td>
                        <td>
                            <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#categoryModal' onclick=\"editCategory({$id}, '{$name}', '{$image}')\">Edit</button>
                            <button class='btn btn-danger btn-sm' onclick='confirmDelete({$id})'>Delete</button>
                        </td>
                        </tr>";
                        $num++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

            </div>
       

        <!-- Category Modal -->
        <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">Category Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="error-container" class="alert alert-danger d-none"></div>
                        <form id="categoryForm" enctype="multipart/form-data" method="post">
                            <input type="hidden" id="categoryId" name="id"> <!-- Hidden input for category ID -->
                            <div class="form-group">
                                <label for="categoryName">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" name="name"
                                    placeholder="Enter category name" required>
                            </div>
                            <div class="form-group">
                                <label for="categoryImage">Category Image</label>
                                <input type="file" class="form-control-file" id="categoryImage" name="image">
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
    $(document).ready(function() {
        $('#categoryForm').on('submit', function(e) {
            e.preventDefault();

            // إعادة تعيين رسالة الخطأ
            $('#error-container').addClass('d-none').text('');

            var formData = new FormData(this);
            $.ajax({
                url: 'process_category.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = JSON.parse(response);

                    if (res.success) {
                        // إظهار رسالة نجاح عند تعديل أو إضافة الفئة
                        Swal.fire({
                            title: 'Success!',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // إعادة تحميل الصفحة أو تحديث الجدول بناءً على رغبتك
                            location.reload();
                        });
                    } else {
                        // عرض رسالة الخطأ في أعلى النموذج
                        $('#error-container').removeClass('d-none').text(res.message);
                    }
                },
                error: function() {
                    $('#error-container').removeClass('d-none').text(
                        'حدث خطأ غير متوقع.');
                }
            });
        });
    });

    function editCategory(id, name, image) {
        $('#categoryId').val(id); // تعيين المعرف في الحقل المخفي
        $('#categoryName').val(name); // تعيين اسم الفئة
        // يمكنك إضافة عرض الصورة في نموذج المعاينة هنا إذا لزم الأمر
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Confirm Deletion',
            text: "Are you sure you want to delete this category?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // إذا تم التأكيد، قم بإرسال الطلب لحذف الفئة
                $.ajax({
                    url: 'delete_category.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        // إظهار رسالة نجاح بعد الحذف
                        Swal.fire('Deleted!', 'Category has been deleted.', 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'An unexpected error occurred while deleting the category.', 'error');
                    }
                });
            }
        });
    }
</script>

</body>

</html>