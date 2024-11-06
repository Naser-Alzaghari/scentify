<?php

require_once 'config.php';
require_once 'OrderManager.php';


$database = new Database();
$pdo = $database->getConnection();


$orderManager = new OrderManager($pdo);

// Get the current page from the URL, default is the first page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

$totalOrders = $orderManager->getOrderCount(); 
$totalPages = ceil($totalOrders / $limit); 

$orders = $orderManager->getOrders($limit, $offset); 
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
        color: #ff4747;
        font-weight: bold;
    }

    .order-status-completed {
        color: #57b657;
        font-weight: bold;
    }

    .order-status-pending {
        color: yellow;
        font-weight: bold;
    }

    .order-status-processing {
        color: gray;
        font-weight: bold;
    }

    /* Sidebar CSS */
.sidebar-offcanvas {
    position: fixed !important; 
    top: 60px!important;
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
                    <a class="nav-link <?php echo ($current_page == 'manage-users.php') ? 'active' : ''; ?>"
                        href="manage-users.php">
                        <i class="icon-head menu-icon"></i>
                        <span class="menu-title">Manage Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage-orders.php') ? 'active' : ''; ?>"
                        href="manage-orders.php">
                        <i class="icon-cart menu-icon"></i>
                        <span class="menu-title">Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage-products.php') ? 'active' : ''; ?>"
                        href="manage-products.php">
                        <i class="icon-box menu-icon"></i>
                        <span class="menu-title">Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage-category.php') ? 'active' : ''; ?>"
                        href="manage-category.php">
                        <i class="icon-tag menu-icon"></i>
                        <span class="menu-title">Category</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage-coupons.php') ? 'active' : ''; ?>"
                        href="manage-coupons.php">
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
                                <input id="searchQuery" type="text" class="search-bar form-control"
                                    placeholder="Search Users..." onkeyup="searchOrders(this.value)">
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
                                        <th>Shipping Address</th>
                                        <th style="white-space: nowrap; width: 1%;">Actions</th>
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
                                        <td><?php echo $order['shipping_address']; ?></td>
                                        <td class="text-left" style="width: fit-content;">
                                            <a href="#" class="p-2 text-info" 
                                                onclick="viewOrderProducts(<?php echo $order['order_id']; ?>)"><i class="fa-solid fa-book-open"></i></a>
                                            <?php if (strtolower($order['order_status']) !== 'cancelled' && strtolower($order['order_status']) !== 'completed'): ?>
                                            <a href="#" class="p-2 text-danger"
                                                onclick="cancelOrder(<?php echo $order['order_id']; ?>)"><i class="fa-solid fa-xmark"></i></i></a>
                                                <a href="#" class="p-2 text-success"
                                                onclick="completeOrder(<?php echo $order['order_id']; ?>)"><i class="fa-solid fa-square-check"></i></a>
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
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
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

    function completeOrder(orderId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, complete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'completeOrder.php',
                    method: 'POST',
                    data: {
                        order_id: orderId
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            Swal.fire(
                                'Completed!',
                                'The order has been completed.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to complete the order.',
                                'error'
                            );
                        }
                    }

                });
            }
        });
    }
    </script>
    <script src="https://kit.fontawesome.com/29bcb0d26a.js" crossorigin="anonymous"></script>
</body>

</html>