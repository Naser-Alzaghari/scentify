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
$orders = getOrderHistory($db, $user_id);
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
                            <button id="updateBtn" class="btn btn-primary text-light">Update Profile</button>
                        </div>

                        <!-- Order History Section -->
                        <div class="tab-pane" id="account">
                            <h6>ORDER HISTORY</h6>
                            <hr>
                            <div class="panel-body">
                                <?php foreach ($orders as $order): ?>
                                <div class="row">
                                    <div class="col-md-1">
                                        <img src="https://bootdey.com/img/Content/user_1.jpg" class="media-object img-thumbnail" alt="Order Image" />
                                    </div>
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-right">
                                                    <label class="label <?php echo strtolower($order['order_status']); ?>">
                                                        <?php echo htmlspecialchars($order['order_status']); ?>
                                                    </label>
                                                </div>
                                                <span><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></span><br>
                                                <span><strong>Total Amount:</strong> $<?php echo htmlspecialchars($order['total_amount']); ?></span><br>
                                                <span><strong>Payment Status:</strong> <?php echo htmlspecialchars($order['payment_status']); ?></span><br>
                                                <span><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></span><br>
                                                <span><strong>Date:</strong> <?php echo htmlspecialchars(date("Y-m-d", strtotime($order['created_at']))); ?></span>
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
// JavaScript to Toggle Update Form and Password Validation (kept as is for functionality)
</script>
</body>
</html>
