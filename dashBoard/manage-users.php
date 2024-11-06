<?php

require 'config.php';
require 'UserManager.php';
session_start();


if (isset($_SESSION['user_role'])) {
    $currentUserRole = $_SESSION['user_role'];
    echo $currentUserRole;
} 


$database = new Database();
$pdo = $database->getConnection();


$userManager = new UserManager($pdo);


$limit = 10; 
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 


$totalUsers = $userManager->getUserCount(currentUserRole: $currentUserRole); 
$totalPages = ceil($totalUsers / $limit); 


$users = $userManager->getUsers($currentUserRole, $limit, $offset);

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
                            <button class="btn btn-secondary text-white" data-toggle="modal" data-target="#addUserModal">Add New
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
                                            <td><?= $user['address'];?></td>
                                            <?php if ($currentUserRole === 'super_admin'): ?>
                                                <td><?= $user['role']; ?></td>
                                            <?php endif; ?>
                                            <td>
                                                <a href="#" class="p-2 text-secondary" data-toggle="modal"
                                                    data-target="#editUserModal<?= $user['user_id']; ?>"><i class='fa-solid fa-pen-to-square'></i></a>
                                                <a href="#" class="p-2 text-danger"
                                                    onclick="confirmDelete(<?= $user['user_id']; ?>)"><i class='fa-solid fa-trash-can'></i></i></a>
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
                                                                <input type="text" class="form-control" name="phone_number" maxlength="14"
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
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" maxlength="14"
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
                                <button type="submit" class="btn btn-secondary text-white">Add User</button>
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
    <script src="https://kit.fontawesome.com/29bcb0d26a.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert inclusion -->
<script src="./Search.js"></script> 
</body>

</html>