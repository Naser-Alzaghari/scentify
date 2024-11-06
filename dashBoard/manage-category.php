<?php
include_once 'config.php';
include_once 'Category.php';

$database = new Database();
$pdo = $database->getConnection();

$category = new Category($pdo);


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$limit = 10; 
$offset = ($page - 1) * $limit; 

$totalCategories = $category->getCategoriesCount(); // Total number of categories
$totalPages = ceil($totalCategories / $limit); // Total pages

// Get all un-deleted classes using LIMIT and OFFSET
$query = "SELECT * FROM categories WHERE is_deleted = 0 LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
ر
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
  /* Sidebar CSS */
.sidebar-offcanvas {
    position: fixed !important;
    top: 60px;!important;
    left: 0 !important;
    width: 250px !important; 
    height: 100vh !important; 
    overflow-y: auto !important; 
    background-color: #f8f9fa; 
    z-index: 1000; 
}

/* Main content CSS */
.content-wrapper {
    margin-left: 250px;
    padding: 20px; 
    transition: margin-left 0.3s ease-in-out; 
}

/* Styling for pagination */
.pagination {
    margin-top: 20px; 
    margin-bottom: 20px; 
    justify-content: center;
}

.pagination .page-item .page-link {
    border: 1px solid #705C53; 
}

.pagination .active .page-link {
    background-color: #705C53;
    color: white;
}

.pagination .disabled .page-link {
    color: #6c757d; 
}

/* Navbar active link */
.nav .nav-link.active {
    background-color: #705C53 !important;
    color: #ffffff !important;
}

.nav .nav-link.active .menu-title {
    color: #ffffff !important; 
}


    </style>
</head>

<body>
    <?php include "header.php" ?>
    <div class="container-fluid page-body-wrapper">
        <!-- Sidebar -->
        <?php
        // Get the current page name
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage-users.php') ? 'active' : ''; ?>" href="manage-users.php">
                        <i class="icon-head menu-icon"></i>
                        <span class="menu-title">Manage Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage-orders.php') ? 'active' : ''; ?>" href="manage-orders.php">
                        <i class="icon-cart menu-icon"></i>
                        <span class="menu-title">Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage-products.php') ? 'active' : ''; ?>" href="manage-products.php">
                        <i class="icon-box menu-icon"></i>
                        <span class="menu-title">Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage-category.php') ? 'active' : ''; ?>" href="manage-category.php">
                        <i class="icon-tag menu-icon"></i>
                        <span class="menu-title">Category</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage-coupons.php') ? 'active' : ''; ?>" href="manage-coupons.php">
                        <i class="icon-tag menu-icon"></i>
                        <span class="menu-title">Coupons</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="container-fluid content-wrapper">
            <h2 class="mt-4">Manage Categories</h2>
        <div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-secondary text-white" data-toggle="modal" data-target="#categoryModal" onclick="clearForm()">Add New Category</button>
            <div class="search-bar-wrapper ml-auto">
                <input id="searchQuery" type="text" class="search-bar form-control" placeholder="Search Category..." onkeyup="searchCategory(this.value)">
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
                        <td><img src='../public/assets/img/gallery/{$image}' alt='Category Image' class='img-thumbnail'></td>
                        <td>
                            <a class='p-2 text-secondary' data-toggle='modal' data-target='#categoryModal' onclick=\"editCategory({$id}, '{$name}', '{$image}')\"><i class='fa-solid fa-pen-to-square'></i></a>
                            <a class='p-2 text-danger' onclick='confirmDelete({$id})'><i class='fa-solid fa-trash-can'></i></a>
                        </td>
                        </tr>";
                        $num++;
                    }
                    ?>
                </tbody>
            </table>
            <nav aria-label="Categories Pagination">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

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
                            <button type="submit" class="btn btn-secondary text-white">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://kit.fontawesome.com/29bcb0d26a.js" crossorigin="anonymous"></script>
        <script src="./Search.js"></script>
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