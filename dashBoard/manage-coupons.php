<?php
require 'config.php';
require 'CouponManager.php';

$couponObj = new CouponManager($pdo);
$couponObj->updateExpiredCoupons(); // تحديث الكوبونات المنتهية الصلاحية تلقائيًا
$coupons = $couponObj->getAllCoupons();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Coupons - Scentify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
   <?php include "header.php"?>
    </div>
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
                    <h2 class="mt-4">Manage Coupons</h2>
                    <div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#couponModal" onclick="clearForm()">Add New Coupon</button>
            <div class="search-bar-wrapper ml-auto">
                <input type="text" class="search-bar form-control" placeholder="Search Coupons..." onkeyup="searchCoupons(this.value)">
            </div>
        </div>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Coupon Code</th>
                        <th>Discount (%)</th>
                        <th>Expiration Date</th>
                        <th>Usage Limit</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="coupons-table-body">
                    <?php foreach ($coupons as $coupon): ?>
                        <tr data-id="<?= $coupon['coupon_id'] ?>">
                            <td><?= $coupon['coupon_id'] ?></td>
                            <td class="coupon-code"><?= $coupon['coupon_code'] ?></td>
                            <td class="discount-percentage"><?= $coupon['discount_percentage'] ?></td>
                            <td class="expiration-date"><?= $coupon['expiration_date'] ?></td>
                            <td class="usage-limit"><?= $coupon['usage_limit'] ?></td>
                            <td class="coupon-status" style="background-color: <?= $coupon['coupon_status'] == 1 ? '#c8e6c9' : '#ffcccb' ?>;">
                                <?= $coupon['coupon_status'] == 1 ? 'Active' : 'Inactive' ?>
                            </td>
                            <td><?= $coupon['created_at'] ?></td>
                            <td>
                                <button onclick="editCoupon(<?= $coupon['coupon_id'] ?>)" class="btn btn-sm btn-primary">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $coupon['coupon_id'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


                    <div class="modal fade" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="couponModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="couponModalLabel">Coupon Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="couponForm" method="POST">
                                        <input type="hidden" name="action" value="add" id="couponAction">
                                        <input type="hidden" name="couponId" id="couponId">
                                        <div class="form-group">
                                            <label for="couponCode">Coupon Code</label>
                                            <input type="text" class="form-control" id="couponCode" name="couponCode" placeholder="Enter coupon code" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="discountPercentage">Discount (%)</label>
                                            <input type="number" class="form-control" id="discountPercentage" name="discountPercentage" placeholder="Enter discount percentage" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="expirationDate">Expiration Date</label>
                                            <input type="date" class="form-control" id="expirationDate" name="expirationDate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="usageLimit">Usage Limit</label>
                                            <input type="number" class="form-control" id="usageLimit" name="usageLimit" placeholder="Enter usage limit" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="couponStatus">Status</label>
                                            <select class="form-control" id="couponStatus" name="couponStatus">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

     
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Clear the form for new coupon
        function clearForm() {
            document.getElementById('couponAction').value = 'add';
            document.getElementById('couponId').value = '';
            document.getElementById('couponCode').value = '';
            document.getElementById('discountPercentage').value = '';
            document.getElementById('expirationDate').value = '';
            document.getElementById('usageLimit').value = '';
            document.getElementById('couponStatus').value = '1';
        }

        // Edit coupon
        function editCoupon(id) {
            const row = document.querySelector(`[data-id="${id}"]`);
            document.getElementById('couponAction').value = 'edit';
            document.getElementById('couponId').value = id;
            document.getElementById('couponCode').value = row.querySelector('.coupon-code').textContent;
            document.getElementById('discountPercentage').value = row.querySelector('.discount-percentage').textContent;
            document.getElementById('expirationDate').value = row.querySelector('.expiration-date').textContent;
            document.getElementById('usageLimit').value = row.querySelector('.usage-limit').textContent;
            document.getElementById('couponStatus').value = row.querySelector('.coupon-status').textContent === 'Active' ? '1' : '0';
            $('#couponModal').modal('show'); // Show the modal after populating the fields
        }

        // Confirm delete action
        function confirmDelete(id) {
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
                    // AJAX request to delete coupon
                    fetch(`process-coupons.php?delete=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire('Deleted!', data.message, 'success').then(() => {
                                    location.reload(); // Reload the page to see the changes
                                });
                            } else {
                                Swal.fire('Error!', data.message, 'error');
                            }
                        });
                }
            });
        }

        // Handle form submission using AJAX
        document.getElementById('couponForm').onsubmit = function(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(this);
            fetch('process-coupons.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Success!', data.message, 'success').then(() => {
                        location.reload(); // Reload the page after the alert
                    });
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            });
        };
    </script>
</body>
</html>
