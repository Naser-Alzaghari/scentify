<?php
session_start();
// تضمين الملفات الضرورية
require_once 'config.php';
require_once 'OrderManager.php';

// إنشاء كائن الاتصال بقاعدة البيانات باستخدام الفئة الموجودة في config.php
$database = new Database();
$pdo = $database->getConnection();

// إنشاء كائن OrderManager وتمرير الاتصال بقاعدة البيانات
$orderManager = new OrderManager($pdo);

// احصل على الصفحة الحالية من عنوان URL، الافتراضي هو الصفحة الأولى
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4; // عدد الطلبات في كل صفحة
$offset = ($page - 1) * $limit;

$totalOrders = $orderManager->getOrderCount(); // إجمالي عدد الطلبات
$totalPages = ceil($totalOrders / $limit); // إجمالي الصفحات

$orders = $orderManager->getOrders($limit, $offset); // جلب الطلبات حسب الصفحة
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Orders - Scentify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Css.css">

    <style>
    .order-status-cancelled {
        background-color: red;
        color: white;
    }

    .order-status-completed {
        background-color: green;
        color: white;
    }

    .order-status-pending {
        background-color: yellow;
        color: black;
    }

    .order-status-processing {
        background-color: orange;
        color: white;
    }
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
.pagination {
    margin-top: 20px; /* إضافة مساحة بين الجدول والـ pagination */
    margin-bottom: 20px; /* إضافة مساحة في الأسفل إذا لزم الأمر */
    justify-content: center; /* لتوسيط العناصر داخل الـ pagination */
}

.pagination .page-item .page-link {
    border: 1px solid #007bff; /* إضافة حدود للروابط */
}

.pagination .active .page-link {
    background-color: #007bff; /* لون خلفية للصفحة النشطة */
    color: white; /* لون النص للصفحة النشطة */
}

.pagination .disabled .page-link {
    color: #6c757d; /* لون النص للصفحات المعطلة */
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

    <div class="container-fluid page-body-wrapper">
       <?php include "header.php" ?>
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
                <h2 class="mt-4">Manage Orders</h2>
                <div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-bar-wrapper ml-auto">
                <input id="searchQuery" type="text" class="search-bar form-control" placeholder="Search Users..." onkeyup="searchOrders(this.value)">
            </div>
        </div>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Total Amount</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Shipping Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="orders-table-body">
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['user_id']; ?></td>
                        <td><?php echo $order['total_amount']; ?></td>
                        <td class="<?php echo 'order-status-' . strtolower($order['order_status']); ?>">
                            <?php echo $order['order_status']; ?>
                        </td>
                        <td><?php echo $order['payment_status']; ?></td>
                        <td><?php echo $order['shipping_address']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="viewOrderProducts(<?php echo $order['order_id']; ?>)">View</button>
                            <?php if (strtolower($order['order_status']) !== 'cancelled' && strtolower($order['order_status']) !== 'completed'): ?>
                                <button class="btn btn-sm btn-danger" onclick="cancelOrder(<?php echo $order['order_id']; ?>)">Cancel</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
           <!-- إضافة كود pagination -->
<nav aria-label="Orders Pagination">
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

            <!-- View Order Products Modal -->
            <div class="modal fade" id="viewProductsModal" tabindex="-1" role="dialog"
                aria-labelledby="viewProductsModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewProductsModalLabel">Order Products</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="orderProductsContainer"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
        
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./Search.js"></script>

    <script>
    // Function to view products in an order
    function viewOrderProducts(orderId) {
        $.ajax({
            url: 'fetch_order_products.php',
            method: 'POST',
            data: {
                order_id: orderId
            },
            success: function(response) {
                $('#orderProductsContainer').html(response);
                $('#viewProductsModal').modal('show');
            }
        });
    }

    // Function to cancel an order with confirmation
    function cancelOrder(orderId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'cancel_order.php',
                    method: 'POST',
                    data: {
                        order_id: orderId
                    },
                    success: function(response) {
                        if (response == 'success') {
                            Swal.fire(
                                'Cancelled!',
                                'The order has been cancelled.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to cancel the order.',
                                'error'
                            );
                        }
                    }
                });
            }
        });
    }
    </script>
</body>

</html>