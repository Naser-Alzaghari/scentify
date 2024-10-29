<?php
include "./includes/include.php";
include('User.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit; 
}

$user_id = $_SESSION['user_id'];
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$userInfo = $user->getUserInfo($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<main class="main" id="top">
  <?php include "Nav.php"; ?>
  <link href="assets/css/theme.css" rel="stylesheet" />
<br>
  <div class="container my-5">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <form method="POST" action="update.php">
          <div class="card-body">
            <h5 class="card-title">User Profile</h5>
            <div class="row">
              <div class="col-md-6">
                <label for="firstName" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($userInfo['first_name']); ?>">
              </div>
              <div class="col-md-6">
                <label for="lastName" class="form-label">Last Name:</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($userInfo['last_name']); ?>">
              </div>
              <div class="col-md-6 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userInfo['email']); ?>">
              </div>
              <div class="col-md-6 mt-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($userInfo['phone_number']); ?>">
              </div>
              <div class="col-md-12 mt-3">
                <label for="address" class="form-label">Address:</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($userInfo['address']); ?>">
              </div>
              <div class="col-md-12 mt-3">
                <label for="password" class="form-label">Password:</label>
                  <input type="password" class="form-control" id="password" name="password"
                      placeholder="Password *" required oninput="checkPasswordRequirements()" onfocus="showPasswordMessage()" onblur="hidePasswordMessage()" />
              
            </div>
            <div class="text-end mt-3">
              <button type="submit" class="btn btn-primary" name="update" id="submit_button">Update</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    <?php if (isset($_SESSION['message'])): ?>
      Swal.fire({
        title: 'success!',
        text: "<?php echo $_SESSION['message']; ?>",
        icon: 'success',
        confirmButtonText: 'Ok'
      });
      <?php unset($_SESSION['message']); ?>
    <?php elseif (isset($_SESSION['error'])): ?>
      Swal.fire({
        title: 'خطأ!',
        text: "<?php echo $_SESSION['error']; ?>",
        icon: 'error',
        confirmButtonText: 'Okay'
      });
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    function checkPasswordRequirements() {
        const password = document.getElementById("password").value;
        const uppercase = /[A-Z]/.test(password);
        const lowercase = /[a-z]/.test(password);
        const number = /[0-9]/.test(password);
        const specialChar = /[!@#$%^&*]/.test(password);
        const minLength = password.length >= 6;

        document.getElementById("length").className = minLength ? "valid" : "invalid";
        document.getElementById("uppercase").className = uppercase ? "valid" : "invalid";
        document.getElementById("lowercase").className = lowercase ? "valid" : "invalid";
        document.getElementById("number").className = number ? "valid" : "invalid";
        document.getElementById("special").className = specialChar ? "valid" : "invalid";
    }
    function showPasswordMessage() {
            document.getElementById("password-message").style.display = "block";
        }

    function hidePasswordMessage() {
            document.getElementById("password-message").style.display = "none";
        }

        document.getElementById().addEventListener('submit',()=>{
          prevent
        }){
  
}
  </script>
</body>
</html>
