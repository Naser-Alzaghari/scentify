<?php
// استدعاء الملفات الضرورية
require 'config.php';
require 'UserManager.php';
session_start();

// التحقق من وجود الدور في الجلسة
if (isset($_SESSION['user_role'])) {
    $currentUserRole = $_SESSION['user_role'];
    echo $currentUserRole;
} 

// إنشاء كائن الاتصال بقاعدة البيانات باستخدام الفئة الموجودة في config.php
$database = new Database();
$pdo = $database->getConnection();

// إنشاء كائن UserManager باستخدام الاتصال بقاعدة البيانات
$userManager = new UserManager($pdo);

// تعيين قيم التصفح الافتراضية
$limit = 4; // عدد المستخدمين لكل صفحة
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // الصفحة الحالية
$offset = ($page - 1) * $limit; // حساب الإزاحة

// الحصول على العدد الكلي للمستخدمين بناءً على دور المستخدم
$totalUsers = $userManager->getUserCount($currentUserRole); // العدد الكلي للمستخدمين
$totalPages = ceil($totalUsers / $limit); // حساب عدد الصفحات الكلي

// جلب المستخدمين للصفحة الحالية
$users = $userManager->getUsers($currentUserRole, $limit, $offset);
// إذا كان الطلب يحتوي على مصطلح بحث، قم بإرجاع نتائج البحث فقط
echo $currentUserRole;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Manage Users - Scentify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Css.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"/>

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
                <h2 class="mt-4">Manage Users</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add New
                                User</button>
                       
                            <div class="search-bar-wrapper ml-auto">
                <input id="searchQuery" type="text" class="search-bar form-control" placeholder="Search Users..." onkeyup="searchUsers(this.value)">
            </div>
                        </div>

                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Birth of Date</th>
                                        <th>Address</th>
                                        <?php if ($currentUserRole === 'super_admin'): ?>
                                            <th>Role</th>
                                        <?php endif; ?>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table-body">
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $user['user_id']; ?></td>
                                            <td><?= $user['first_name']; ?></td>
                                            <td><?= $user['last_name']; ?></td>
                                            <td><?= $user['email']; ?></td>
                                            <td><?= $user['phone_number']; ?></td>
                                            <td><?= $user['birth_of_date']; ?></td>
                                            <td><?= $user['address'];?></td>
                                            <?php if ($currentUserRole === 'super_admin'): ?>
                                                <td><?= $user['role']; ?></td>
                                            <?php endif; ?>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#editUserModal<?= $user['user_id']; ?>">Edit</button>
                                                <a href="#" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete(<?= $user['user_id']; ?>)">Delete</a>
                                            </td>
                                        </tr>

                                        <!-- Edit User Modal -->
                                        <div class="modal fade" id="editUserModal<?= $user['user_id']; ?>" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit User</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="editUserForm" action="update_user.php" method="POST">
                                                            <input type="hidden" name="user_id"
                                                                value="<?= $user['user_id']; ?>">
                                                            <div class="form-group">
                                                                <label>First Name</label>
                                                                <input type="text" class="form-control" name="first_name"
                                                                    value="<?= $user['first_name']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Last Name</label>
                                                                <input type="text" class="form-control" name="last_name"
                                                                    value="<?= $user['last_name']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input type="email" class="form-control" name="email"
                                                                    value="<?= $user['email']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Phone Number</label>
                                                                <input type="text" class="form-control" name="phone_number"
                                                                    value="<?= $user['phone_number']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Birth Date</label>
                                                                <input type="date" class="form-control" name="birth_of_date"
                                                                    value="<?= $user['birth_of_date']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Address</label>
                                                                <input class="form-control" name="address"
                                                                    value="<?= $user['address']; ?>">
                                                            </div>
                                                            <?php if ($currentUserRole === 'super_admin'): ?>
                                                                <div class="form-group">
                                                                    <label>Role</label>
                                                                    <select class="form-control" name="role">
                                                                        <option value="user" <?= $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                                                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                                        <option value="super_admin"
                                                                            <?= $user['role'] == 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                                                                    </select>
                                                                </div>
                                                            <?php endif; ?>
                                                            <button type="submit" class="btn btn-primary">Update
                                                                User</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <div class="pagination-container">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?= $page <= 1 ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Add User Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="error-container" class="alert alert-danger d-none"></div>
                            <form id="userForm" action="add_user.php" method="POST">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="Enter First Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="Enter Last Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter Email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter Password" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                                        placeholder="Enter Phone Number">
                                </div>
                                <div class="form-group">
                                    <label for="birth_of_date">Birth Date</label>
                                    <input type="date" class="form-control" id="birth_of_date" name="birth_of_date"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"
                                        placeholder="Enter Address"></textarea>
                                </div>
                                <?php if ($currentUserRole === 'super_admin'): ?>
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select class="form-control" id="role" name="role">
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                            <option value="super_admin">Super Admin</option>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="Script.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert inclusion -->
<script src="./Search.js"></script> 
</body>

</html>