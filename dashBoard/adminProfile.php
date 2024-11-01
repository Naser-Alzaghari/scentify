<?php
session_start();
include 'config.php';
include 'admin.php';

if (!isset($_SESSION['admin_user_id'])) {
    header("Location: login.php"); // توجيه المستخدم إلى صفحة تسجيل الدخول إذا لم يكن مسجل الدخول
    exit;
}

$user_id = $_SESSION['admin_user_id'];
$database = new Database();
$db = $database->getConnection();
$admin = new Admin($db);
$userInfo = $admin->getUserInfo($user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Css.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"/>


    <style>
        /* تحسين صورة البروفايل */
.profile-picture-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.table {
    width: 100%;
    background-color: #fff;
    border-collapse: separate;
    border-spacing: 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.table th, .table td {
    padding: 12px 20px;
    text-align: left;
}

.table th {
    background-color: #4CAF50; /* اللون الأساسي لعناوين الأعمدة */
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
}

.table td {
    background-color: #f9f9f9; /* لون خلفية خلايا البيانات */
    color: #333;
}

.table tr:nth-child(even) td {
    background-color: #f2f2f2; /* لون مختلف للصفوف الزوجية */
}

/* تحسين زر التحديث ليصبح متناسقاً */
.btn-primary {
    background-color: #4CAF50;
    border: none;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #45a049; /* لون أغمق عند التمرير */
}

/* تحسين النصوص لجعلها أكثر جاذبية */
.table th, .table td {
    font-size: 16px;
}

.card-body h6 {
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
}

        .btn-primary {
    margin-top: 20px; /* يبعد الزر 20 بكسل عن الجدول */
    border-radius: 0;
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

<div class="card-body text-center">
    <!-- صورة البروفايل مع التنسيقات الجديدة -->
    <div class="profile-picture-container">
        <img src="<?php echo htmlspecialchars($userInfo['profile_picture'] ?? '../public/assets/img/gallery/profile_image.jpg'); ?>" alt="Admin Profile Picture" class="profile-picture">
    </div>
    
    <!-- معلومات البروفايل -->
    <div class="profile-info mt-4">
        <h6>YOUR PROFILE INFORMATION</h6>
        <hr>
        <table class="table">
            <tr>
                <th>First Name</th>
                <td><?php echo htmlspecialchars($userInfo['first_name']); ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo htmlspecialchars($userInfo['last_name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($userInfo['email']); ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo htmlspecialchars($userInfo['phone_number']); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($userInfo['address']); ?></td>
            </tr>
        </table>

        <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
            Update Profile
        </button>
    </div>
</div>


<!-- Modal for Update Profile -->
<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProfileModalLabel">Update Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateProfileForm" method="POST" action="updateAdminProfile.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="profilePicture">Profile Picture</label>
                        <input type="file" name="profilePicture" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="firstName">First Name</label>
                        <input type="text" name="firstName" class="form-control" value="<?php echo htmlspecialchars($userInfo['first_name']); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="lastName">Last Name</label>
                        <input type="text" name="lastName" class="form-control" value="<?php echo htmlspecialchars($userInfo['last_name']); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($userInfo['email']); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($userInfo['phone_number']); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($userInfo['address']); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password (Leave blank if not changing)</label>
                        <input type="password" name="password" class="form-control" placeholder="New Password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert Library -->

<script>
    function showAlert(type, title, message) {
        Swal.fire({
            icon: type,
            title: title,
            html: message
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has("success")) {
            showAlert('success', 'Profile Updated', 'Your profile has been updated successfully!');
        } else if (urlParams.get("error") === "weak_password") {
            showAlert('error', 'Weak Password', 'Password must meet the following criteria:<br>- At least 12 characters<br>- At least one uppercase letter<br>- At least one lowercase letter<br>- At least one number<br>- At least one special character');
        } else if (urlParams.get("error") === "invalid_image_type") {
            showAlert('error', 'Invalid Image', 'Only JPEG, PNG, and GIF images are allowed.');
        } else if (urlParams.get("error") === "image_size_exceeded") {
            showAlert('error', 'Image Too Large', 'The image size must not exceed 2 MB.');
        } else if (urlParams.get("error") === "upload_failed") {
            showAlert('error', 'Upload Failed', 'There was an error uploading the image. Please try again.');
        } else if (urlParams.has("error")) {
            showAlert('error', 'Error', 'There was an error updating your profile. Please try again.');
        }
    });
</script>


</body>
</html>
