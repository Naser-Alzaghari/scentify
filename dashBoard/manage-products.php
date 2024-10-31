<?php
session_start();
// تضمين ملفات الاتصال بقاعدة البيانات والفئات الضرورية
require_once 'config.php';
require_once 'Product.php';

// إنشاء كائن الاتصال بقاعدة البيانات باستخدام الفئة الموجودة في config.php
$database = new Database();
$pdo = $database->getConnection();

// إنشاء كائن Product وتمرير الاتصال بقاعدة البيانات
$productManager = new Product($pdo);

// إعداد pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4; // عدد المنتجات في كل صفحة
$offset = ($page - 1) * $limit;

$totalProducts = $productManager->getProductsCount(); // إجمالي عدد المنتجات
$totalPages = ceil($totalProducts / $limit); // إجمالي الصفحات

$products = $productManager->getProducts($limit, $offset); // جلب المنتجات حسب الصفحة
$categories = $productManager->getCategories();

// Create a mapping of category IDs to names
$categoryNames = [];
foreach ($categories as $category) {
    $categoryNames[$category['category_id']] = $category['category_name'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Products - Scentify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="Css.css">
    <style>
    .search-bar-wrapper {
        max-width: 400px;
        /* أقصى عرض لشريط البحث */
    }

    .search-bar {
        background-color: white;
        width: 250px;
        /* عرض كامل للعنصر */
        padding: 10px 20px;
        /* حشوة داخلية */
        border: none;
        /* إزالة الحدود */
        border-radius: 50px;
        /* زوايا دائرية */
        outline: none;
        /* إزالة الخط الخارجي عند التركيز */
        font-size: 16px;
        /* حجم الخط */
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        /* ظل خفيف */
        transition: all 0.3s ease;
        /* تأثيرات انتقالية */
    }

    .search-bar:focus {
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        /* ظل خفيف */
    }

    .pagination {
        margin-top: 20px;
        /* إضافة مساحة بين الجدول والـ pagination */
        margin-bottom: 20px;
        /* إضافة مساحة في الأسفل إذا لزم الأمر */
        justify-content: center;
        /* لتوسيط العناصر داخل الـ pagination */
    }

    .pagination .page-item .page-link {
        border: 1px solid #007bff;
        /* إضافة حدود للروابط */
    }

    .pagination .active .page-link {
        background-color: #007bff;
        /* لون خلفية للصفحة النشطة */
        color: white;
        /* لون النص للصفحة النشطة */
    }

    .pagination .disabled .page-link {
        color: #6c757d;
        /* لون النص للصفحات المعطلة */
    }
    .nav .nav-link.active {
    background-color: #007bff !important; /* لون الخلفية عند التفعيل */
    color: #ffffff !important; /* لون النص الأبيض */
}

.nav .nav-link.active .menu-title {
    color: #ffffff !important; /* تأكد من أن النص داخل العنصر يكون لونه أبيض أيضًا */
}
    </style>
</head>

<body>
    <?php include "header.php" ?>
    </div>
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

        <div class="main-panel">
            <div class="content-wrapper">
                <h2 class="mt-4">Manage Products</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#productModal"
                                onclick="clearForm()">Add New Product</button>
                            <div class="search-bar-wrapper ml-auto">
                                <input id="searchQuery" type="text" class="search-bar form-control"
                                    placeholder="Search Products..." onkeyup="searchProducts(this.value)">
                            </div>
                        </div>

                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Stock Quantity</th>
                                        <th>Category</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="products-table-body">
                                    <?php foreach ($products as $index => $product): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo $product['product_name']; ?></td>
                                        <td><?php echo $product['product_description']; ?></td>
                                        <td><?php echo $product['price']; ?></td>
                                        <td><?php echo $product['stock_quantity']; ?></td>
                                        <td><?php echo isset($categoryNames[$product['category_id']]) ? $categoryNames[$product['category_id']] : 'N/A'; ?>
                                        </td>
                                        <td><img src="../public/assets/img/gallery/<?php echo $product['product_image']; ?>" alt="Product Image"
                                                class="img-thumbnail" width="50"></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#productModal"
                                                onclick="editProduct(<?php echo $product['product_id']; ?>, '<?php echo $product['product_name']; ?>', '<?php echo $product['product_description']; ?>', '<?php echo $product['price']; ?>', '<?php echo $product['stock_quantity']; ?>', '<?php echo $product['category_id']; ?>', '<?php echo $product['product_image']; ?>')">Edit</button>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="confirmDelete(<?php echo $product['product_id']; ?>)">Delete</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <nav aria-label="Products Pagination">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>"
                                            aria-label="Previous">
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


                <!-- Modal for Adding/Editing Product -->
                <div class="modal fade" id="productModal" tabindex="-1" role="dialog"
                    aria-labelledby="productModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productModalLabel">Product Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="productForm" method="POST" enctype="multipart/form-data"
                                    action="product_action.php">
                                    <input type="hidden" name="action" id="action" value="add">
                                    <input type="hidden" name="productId" id="productId">
                                    <div class="form-group">
                                        <label for="productName">Product Name</label>
                                        <input type="text" class="form-control" id="productName" name="productName"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="productDescription">Description</label>
                                        <textarea class="form-control" id="productDescription" name="productDescription"
                                            rows="4" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="productPrice">Price</label>
                                        <input type="number" class="form-control" id="productPrice" name="productPrice"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="productStock">Stock Quantity</label>
                                        <input type="number" class="form-control" id="productStock" name="productStock"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="productCategory">Category</label>
                                        <select class="form-control" id="productCategory" name="productCategory"
                                            required>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['category_id']; ?>">
                                                <?php echo $category['category_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="productImage">Product Image</label>
                                        <input type="file" class="form-control" id="productImage" name="productImage"
                                            accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>

                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"> </script>
<<<<<<< HEAD
                <script src = "./Search.js" > </script>
               
=======
                <script script src = "./Search.js" >
                </script>
                
>>>>>>> c520d278fc1dc6a2ce8c2b1354f9c90458b86d99
                <script>
                function clearForm() {
                    document.getElementById('productForm').reset();
                    document.getElementById('action').value = 'add';
                }

                function editProduct(id, name, description, price, stock, category, image) {
                    document.getElementById('productId').value = id;
                    document.getElementById('productName').value = name;
                    document.getElementById('productDescription').value = description;
                    document.getElementById('productPrice').value = price;
                    document.getElementById('productStock').value = stock;
                    document.getElementById('productCategory').value = category;
                    document.getElementById('action').value = 'edit';
                }

                function confirmDelete(productId) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "POST",
                                url: "product_action.php",
                                data: {
                                    action: 'delete',
                                    productId: productId
                                },
                                success: function(response) {
                                    let jsonResponse = JSON.parse(response);
                                    if (jsonResponse.status === 'success') {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: jsonResponse.message,
                                            icon: 'success',
                                            timer: 3000,
                                            showConfirmButton: false
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: jsonResponse.message,
                                            icon: 'error',
                                            timer: 3000,
                                            showConfirmButton: false
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'An error occurred while deleting the product.',
                                        icon: 'error',
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                }
                            });
                        }
                    });
                }

                document.getElementById('productForm').onsubmit = function(event) {
                    event.preventDefault();

                    const formData = new FormData(this);
                    fetch('product_action.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while processing your request.',
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        });
                };
                </script>
<script>const searchInput = document.getElementById('searchQuery');
const tables = document.getElementsByClassName('table-container');

// Loop through each table
for (let i = 0; i < tables.length; i++) {
    const dataTable = tables[i].querySelector('table');
    const rows = dataTable.getElementsByTagName('tr');

    // Add event listener to the search input
    searchInput.addEventListener('input', function () {
        const searchText = searchInput.value.toLowerCase();

        // Loop through all table rows, excluding the header row
        for (let i = 1; i < rows.length; i++) { // Start loop from index 1 to skip the header row
            const cells = rows[i].getElementsByTagName('td');
            let found = false;

            // Loop through all table cells in the current row
            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent.toLowerCase();
                if (cellText.includes(searchText)) {
                    found = true;
                    break;
                }
            }

            // Show or hide the row based on the search result
            if (found) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
}</script>


</body>

</html>