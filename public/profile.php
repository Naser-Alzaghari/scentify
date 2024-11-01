<?php
include "orderHistory.php";

class HTMLDocument {
    private $title;
    private $stylesheets = [];
    private $scripts = [];

    public function __construct($title) {
        $this->title = $title;
        $this->addStylesheet("assets/css/theme.css");
        $this->addStylesheet("assets/css/style.css");
    }

    public function addStylesheet($href) {
        $this->stylesheets[] = $href;
    }

    public function addScript($src) {
        $this->scripts[] = $src;
    }

    public function renderHead() {
        echo "<title>$this->title</title>";
        foreach ($this->stylesheets as $href) {
            echo "<link href='$href' rel='stylesheet' />";
        }
        foreach ($this->scripts as $src) {
            echo "<script src='$src'></script>";
        }
    }
}
?>

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
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/theme.css" rel="stylesheet" />

    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/gallery/title_logo.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/gallery/title_logo.png">
    <meta name="msapplication-TileImage" content="assets/img/gallery/title_logo.png">
    <meta name="theme-color" content="#ffffff">

    <style type="text/css">
        /* Include any extra styling you need */
        body {
            margin-top: 20px;
            background-color: #e2e8f0;
        }
        .main-body {
            padding: 15px;
        }
        .main {
            margin-top: 50px;
        }
        #password-message {
            display: none;
        }
        .valid {
            color: green;
        }
        .invalid {
            color: red;
        }
    </style>
</head>
<body>
<?php
    $document = new HTMLDocument("Scentify");
    $document->renderHead();

    $navbar = new Navbar();
    $navbar->render();

    $alert = new Alert();
    $alert->showAlert();
?>
    <br class="mt-5">
    <br class="mt-5">
    <br class="mt-5">
    <br class="mt-5">

<main class="main" id="top">
    <div class="container my-5">
        <div class="row gutters-sm">
            <div class="col-md-4 d-none d-md-block">
                <div class="card">
                    <div class="card-body">
                        <nav class="nav flex-column nav-pills nav-gap-y-1">
                            <a href="#profile" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded active">
                                Profile Information
                            </a>
                            <a href="#account" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded">
                               Order History
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header border-bottom mb-3 d-flex d-md-none">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a href="#profile" data-toggle="tab" class="nav-link has-icon active">Profile Information</a>
                            </li>
                            <li class="nav-item">
                                <a href="#account" data-toggle="tab" class="nav-link has-icon">Order History</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="card-body tab-content">
                        <!-- Profile Information Section -->
                        <div class="tab-pane active" id="profile">
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

                            <!-- Button to show update form -->
                            <button id="updateBtn" class="btn btn-primary text-light">Update Profile</button>

                            <!-- Update Form Section (Initially Hidden) -->
                            <div id="updateFormSection" style="display:none; margin-top: 20px;">
                                <h5>Update Your Profile</h5>
                                <form method="POST" action="update.php">
                                    <div class="form-group">
                                        <label for="firstName">First Name</label>
                                        <input type="text" name="firstName" class="form-control" value="<?php echo htmlspecialchars($userInfo['first_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName">Last Name</label>
                                        <input type="text" name="lastName" class="form-control" value="<?php echo htmlspecialchars($userInfo['last_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($userInfo['email']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($userInfo['phone_number']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($userInfo['address']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password (Leave blank if not changing)</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Password *" required oninput="checkPasswordRequirements()" onfocus="showPasswordMessage()" onblur="hidePasswordMessage()" />
                                    </div>
                                    <div id="password-message">
                                        <p>Password must contain:</p>
                                        <ul>
                                            <li id="length" class="invalid">At least 6 characters</li>
                                            <li id="uppercase" class="invalid">At least one uppercase letter</li>
                                            <li id="lowercase" class="invalid">At least one lowercase letter</li>
                                            <li id="number" class="invalid">At least one number</li>
                                            <li id="special" class="invalid">At least one special character</li>
                                        </ul>
                                    </div>
                                    <button type="submit" name="update" class="btn btn-success">Save Changes</button>
                                </form>
                            </div>
                        </div>

                        <!-- Account Settings Section (For future extension) -->
                        <div class="tab-pane" id="account">
                            <h6>ORDER HISTORY</h6>
                            <hr>
                            <div class="panel-body">
                                <?php foreach ($orders as $order): ?>
                                <div class="row">
                                    <div class="col-md-1">
                                        <img src="<?php echo $order['image']; ?>" class="media-object img-thumbnail" />
                                    </div>
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-right">
                                                    <label class="label <?php echo strtolower($order['status']); ?>">
                                                        <?php echo $order['status']; ?>
                                                    </label>
                                                </div>
                                                <span><strong>Order ID:</strong> <?php echo $order['id']; ?></span><br>
                                                <span><strong>Product:</strong>
                                                 <?php 
                                                //  echo $order['product_name']; 
                                                 ?>
                                            </span><br>
                                                <span><strong>Quantity:</strong> <?php echo $order['quantity']; ?></span><br>
                                                <span><strong>Date:</strong> <?php echo date("Y-m-d", strtotime($order['date'])); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
// JavaScript to Toggle Update Form
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

document.getElementById('updateBtn').addEventListener('click', function() {
    var formSection = document.getElementById('updateFormSection');
    if (formSection.style.display === "none") {
        formSection.style.display = "block";
    } else {
        formSection.style.display = "none";
    }
});

// Password validation before form submission
const updateForm = document.querySelector('form[action="update.php"]');
updateForm.addEventListener('submit', function(e) {
    const password = document.getElementById("password").value;
    const minLength = password.length >= 6;
    const uppercase = /[A-Z]/.test(password);
    const lowercase = /[a-z]/.test(password);
    const number = /[0-9]/.test(password);
    const specialChar = /[!@#$%^&*]/.test(password);

    if (!minLength || !uppercase || !lowercase || !number || !specialChar) {
        e.preventDefault(); // Prevent form submission
        swal("Error!", "Please make sure your password meets all requirements.", "error");
    } else {
        swal("Success!", "Your changes have been saved!", "success");
    }
});
</script>
</body>
</html>
