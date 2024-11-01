<?php
session_start();
include 'conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("location: LoginPage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the order_id from the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Query to get order details and items for the specific order ID
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
        o.order_id = :order_id AND o.user_id = :user_id
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute(['order_id' => $order_id, 'user_id' => $user_id]);
    $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to fetch user details
    $query_user = "SELECT first_name, last_name, email, phone_number, address FROM users WHERE user_id = :user_id";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->execute(['user_id' => $user_id]);
    $user_checkout = $stmt_user->fetch(PDO::FETCH_ASSOC);
} else {
    // Redirect if no order_id is provided
    header("location: orderHistory.php"); // Or some other page
    exit();
}

// Check if orderDetails has any data
if (empty($orderDetails)) {
    // Handle case when no order details are found
    echo "No order details found.";
    exit();
}

// Rest of your HTML and PHP code here...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/theme.css">
    <title>Order Completion</title>
</head>
<body>
<?php include "nav_bar.php"; ?>

<main class="main" id="top">
    <section class="bg-light-gradient border-bottom border-white border-5">
        <div class='bg-holder overlay overlay-light'
             style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'></div>

        <div class="container py-5">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-10 col-xl-8">
                    <div class="card" style="border-radius: 10px;">
                        <div class="card-header px-4 py-5">
                            <h5 class="text-muted mb-0">Thanks for your Order, <span style="color: #a8729a;">
                                <?php echo htmlspecialchars($user_checkout['first_name'] . ' ' . $user_checkout['last_name']); ?>
                            </span>!</h5>
                        </div>
                        <div class="card-body p-4">
                        <p class="lead fw-normal mb-0" style="color: #a8729a;">Order Status: 
    <span class="fw-bold"><?php echo htmlspecialchars($orderDetails[0]['order_status']); ?></span>
</p>

                            <?php if (!empty($orderDetails)) : ?>
                                <?php foreach ($orderDetails as $item) : ?>
                                    <div class="card shadow-0 border mb-4">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <img src="<?php echo htmlspecialchars($item['product_image']); ?>" class="img-fluid" alt="Product Image">
                                                </div>
                                                <div class="col-md-3 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0"><?php echo htmlspecialchars($item['product_description']); ?></p>
                                                </div>
                                                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0 small">Qty: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                                </div>
                                                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0 small">$<?php echo htmlspecialchars($item['total_price']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="d-flex justify-content-between pt-2">
                                    <p class="fw-bold mb-0">Order Details</p>
                                    <p class="text-muted mb-0"><span class="fw-bold me-4">Total</span> $<?php echo htmlspecialchars($orderDetails[0]['total_amount']); ?></p>
                                </div>

                                <div class="d-flex justify-content-between pt-2">
                                    <p class="text-muted mb-0">Invoice Number: <?php echo htmlspecialchars($orderDetails[0]['order_id']); ?></p>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <p class="text-muted mb-0">Order Date: <?php echo htmlspecialchars($orderDetails[0]['order_checkout_date']); ?></p>
                                </div>
                            <?php else : ?>
                                <p class="text-muted">No items found in your latest order.</p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer border-0 px-4 py-5"
                            style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                            <h5 class="d-flex align-items-center justify-content-end text-white text-uppercase mb-0">Total
                                Paid: <span class="h2 mb-0 ms-2">$<?php echo htmlspecialchars($orderDetails[0]['total_amount']); ?></span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include "footer.html"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

