<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['user_id'])) {
    header("location: LoginPage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to get the latest order and its items details
$query = "
SELECT 
    o.order_id,
    o.total_amount,
    o.order_status,
    o.payment_status,
    o.shipping_address,
    o.created_at,
    o.order_checkout_date,
    o.comments,
    oi.order_item_id,
    oi.product_id,
    oi.quantity,
    oi.price,
    (oi.quantity * oi.price) AS total_price,
    p.product_image,
    p.product_description
FROM 
    orders o
JOIN 
    order_items oi ON o.order_id = oi.order_id
JOIN 
    products p ON oi.product_id = p.product_id
WHERE 
    o.user_id = :user_id 
ORDER BY 
    o.order_checkout_date DESC 
LIMIT 1
";

$stmt = $conn->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Get the latest order ID to fetch all items in it
if (!empty($orderDetails)) {
    $order_id = $orderDetails[0]['order_id'];
    
    $query = "
    SELECT `order_item_id`, `order_id`, products.product_id, `quantity`, products.price, `on_cart`, product_image, product_description, order_items.price total_price FROM `order_items` JOIN products on products.product_id = order_items.product_id WHERE `order_id` = :order_id
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute(['order_id' => $order_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Query to fetch user details
    $query_user = "SELECT first_name, last_name, email, phone_number, total_amount, order_status, order_checkout_date, address FROM users JOIN orders WHERE users.user_id = :user_id and `order_id` = :order_id";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->execute(['user_id' => $user_id, 'order_id'=> $order_id]);
    $user_checkout = $stmt_user->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <title>Order Completion</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/gallery/title_logo.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/gallery/title_logo.png">
    <meta name="msapplication-TileImage" content="assets/img/gallery/title_logo.png">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/theme.css">
    
</head>

<body>


<main class="main d-flex flex-column" id="top" style="min-height: 100vh;">
    <section class="d-flex bg-light-gradient flex-grow-1">
        <div class='bg-holder overlay overlay-light'
             style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'></div>

        <div class="container py-5">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-10 col-xl-8">
                    <div class="card border-5 rounded">
                        <div class="card-header px-4 py-5">
                            <h5 class="text-700 mb-3">Thanks for your Order, <span style="color: #cfa1a4;">
                                <?php echo htmlspecialchars($user_checkout['first_name'] . ' ' . $user_checkout['last_name']); ?>
                            </span>!</h5>
                            <p class="text-700 mb-0">Invoice Number: <?php echo htmlspecialchars($order_id); ?></p>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include "footer.html"; ?>
</main>
<?php include "nav_bar.php"; ?>


<script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script>
    feather.replace();
    </script>
    <script src="assets/js/theme.js"></script>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>