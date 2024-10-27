<?php
require 'config.php';
require 'OrderManager.php';

$orderManager = new OrderManager($pdo);
$orders = $orderManager->getOrders();
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

        <div class="main-panel">
            <div class="content-wrapper">
                <h2 class="mt-4">Manage Orders</h2>
                <div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="search-bar-wrapper ml-auto">
                <input type="text" class="search-bar form-control" placeholder="Search Orders..." onkeyup="searchOrders(this.value)">
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
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024.
                        Scentify. All rights reserved.</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made
                        with 🤍<i class="ti-heart text-danger ml-1"></i></span>
                </div>
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a
                            href="https://www.scentify.com/" target="_blank">Scentify</a></span>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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