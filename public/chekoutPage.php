<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['user_id'])) {
    header("location: LoginPage.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = isset($_POST['order_id']) ? $_POST['order_id'] : null;
    $total_quantity = $_POST['total_quantity'];
    $length = $_POST['length_of_order'];
    $productQuantitiesJson = $_POST['total_quantity'];
}

$query = "SELECT 
    p.product_image,
    p.product_description,
    oi.quantity AS total_quantity,
    (oi.quantity * p.price) AS total_price,
    oi.product_id,
    oi.order_item_id,
    o.order_id
FROM 
    orders o
JOIN 
    order_items oi ON o.order_id = oi.order_id
JOIN 
    products p ON oi.product_id = p.product_id
WHERE 
    o.user_id = :user_id
    AND oi.on_cart = 1";

$stmt = $conn->prepare($query);

if (!isset($_SESSION['user_id'])) {
    header("location: LoginPage.php");
    exit();
}
// Bind the user_id parameter. Replace 1 with $_SESSION['user_id'] if it's coming from session
$user_id = $_SESSION['user_id']; // or use $_SESSION['user_id'] if session is set
$stmt->execute(['user_id' => $user_id]);

// Fetch the results if needed
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);




$totalAmount = array_sum(array_column($results, 'total_price'));
$user_id = $_SESSION['user_id'];
// model update
$query_user = "SELECT   `first_name`,`last_name`,`email`,`phone_number`,`address`  FROM `users` WHERE `user_id`=:user_id ;";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bindParam('user_id', $user_id);
$stmt_user->execute();
$user_checkout = $stmt_user->fetch(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <title>Checkout Page</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/gallery/title_logo.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/gallery/title_logo.png">
    <meta name="msapplication-TileImage" content="assets/img/gallery/title_logo.png">
    <link rel="stylesheet" href="./assets/css/theme.css">
    <link rel="stylesheet" href="./assets/css/style.css">

</head>

<body>

    <main class="main" id="top">
        <section class="bg-light-gradient border-bottom border-white border-5">
            <div class='bg-holder overlay overlay-light'
                style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'></div>

            <div class="container py-2">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-lg-10 col-xl-8">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header px-4 py-5">
                                <h3 style="color: #cfa1a4;">Checkout</h3>

                            </div>
                            <form action="checkout.php" method="POST">
                                <div class="mb-4">
                                    <input type="hidden" name="order_id" value="<?= $order_id ?>">
                                    <input type="hidden" name="total_quantity" value='<?php echo $productQuantitiesJson; ?>'>
                                </div>

                                <div class="container">
                                    <div class="mb-4">
                                        <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $user_checkout['first_name'] . ' ' . $user_checkout['last_name']; ?>" required readonly>
                                    </div>

                                    <div class="mb-4">
                                        <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $user_checkout['email']; ?>" required readonly>
                                    </div>

                                    <div class="mb-4">
                                        <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo $user_checkout['address']; ?>" required>
                                    </div>

                                    <div class="mb-4">
                                        <input type="text" class="form-control" name="phone" placeholder="Phone number" value="<?php echo $user_checkout['phone_number']; ?>" readonly required>
                                    </div>

                                    <div class="mb-4">
                                        <textarea class="form-control" name="comments" placeholder="Order comments" rows="4"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <p id="coupon_error"></p>
                                        <input type="text" class="form-control mb-2" id="coupon" name="coupon" placeholder="Coupon" style="border-bottom-left-radius:0; border-bottom-right-radius:0;">
                                        <button type="button" class="btn rounded" style="background-color: #cfa1a4; color: white" onclick="checkCoupon();">Add coupon</button>
                                    </div>

                                    <div class="mb-4">
                                        <input type="radio" name="payment" value="cash on delivery" required>
                                        <label class="ms-1 mb-0">Cash on delivery</label>
                                        <input type="hidden" name="up_to_date_total_amount" id="checkout-total-hidden" value="<?= $totalAmount; ?>">
                                    </div>

                                    <hr class="mb-4">


                                </div>

                                <div class="card-footer border-0 px-4 py-4"
                                    style="background-color: #cfa1a4; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; color: #F5F5F7">
                                    <p style="font-size: 1.5rem;">Total Amount: <span class="me-3 d-none" style="text-decoration: line-through;" id="price_before"></span><span id="checkout-total">$<?php echo $totalAmount; ?></span></p>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn" style="background-color: #F5F5F7;" onclick="window.location.href='/scentify/public/cart.php';">Close</button>
                                        <button type="submit" class="btn btn-primary1 ms-2" style="background-color: #4a4a4a;">Checkout</button>
                                    </div>
                                </div>


                            </form>


                        </div>
                    </div>
                </div>
        </section>
    </main>
    <?php include "nav_bar.php"; ?>
    <?php include "footer.html"; ?>

    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script>
        feather.replace();
    </script>
    <script src="assets/js/theme.js"></script>
    <script>
        function checkCoupon() {
            // check coupon
            // 1. Check the format of the coupon
            const coupon_string = $("input[name=coupon]").val();
            const regex = /^\d{2}[A-Z]{4}$/;
            if (!regex.test(coupon_string)) {
                $('p#coupon_error').removeClass('text-danger').removeClass('text-success').addClass('text-danger');
                $('p#coupon_error').html('Invalid coupon format');
                return;
            } else {
                $('p#coupon_error').html('');
            }

            // 2. Check if the coupon is valid or not (Maybe expired, or simple it does not exist).
            $.ajax({
                type: "GET",
                url: "verify_coupon.php",
                data: {
                    coupon: coupon_string
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == "success") {
                        if (response.is_valid) {
                            $('p#coupon_error').html('Coupon is valid');
                            $('p#coupon_error').removeClass('text-danger').removeClass('text-success').addClass('text-success');
                            var discount_percentage = parseFloat(response.discount_percentage);
                            let final_value = total_amount_global - (total_amount_global * (discount_percentage / 100));
                            console.log(total_amount_global);
                            document.getElementById("price_before").classList.remove("d-none");
                            $('#checkout-total').html(final_value.toFixed(2));
                            $('input[name=up_to_date_total_amount]').val(final_value.toFixed(2));
                        } else {
                            // Its not valid.
                            $('p#coupon_error').removeClass('text-danger').removeClass('text-success').addClass('text-danger');
                            $('p#coupon_error').html('Coupon is not valid');
                        }
                    }
                }
            });
        }
        var total_amount_global = <?= $totalAmount ?>;
        document.getElementById("price_before").innerHTML = "$"+total_amount_global;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    
</body>

</html>