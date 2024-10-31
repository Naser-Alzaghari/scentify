<?php
include "./includes/include.php";
include('User.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("location: LoginPage.php");
    exit; 
}

$user_id = $_SESSION['user_id'];
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$userInfo = $user->getUserInfo($user_id);
?>
<br><br><br>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<main class="main" id="top">
  <?php include "Nav.php"; ?>
  <link href="assets/css/theme.css" rel="stylesheet" />

    <div class="container my-5">
        <h5 class="card-title">User Profile</h5>
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
        <a href="edit.php" class="btn btn-primary">Edit Profile</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
