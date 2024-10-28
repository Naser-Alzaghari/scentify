<?php 
include 'conn.php';

$query = "SELECT 
    p.product_image,
    p.product_description,
    oi.quantity AS total_quantity,
    (oi.quantity * p.price) AS total_price,
    oi.product_id,
    oi.order_item_id
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

// Bind the user_id parameter. Replace 1 with $_SESSION['user_id'] if it's coming from session
$user_id = 1; // or use $_SESSION['user_id'] if session is set
$stmt->execute(['user_id' => $user_id]);

// Fetch the results if needed
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalAmount = array_sum(array_column($results, 'total_price'));

?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart</title>
    <link href="assets/css/theme.css" rel="stylesheet" />
    <style>
        @media (min-width: 1025px) {
            .h-custom {
                height: 100vh !important;
            }
        }
        .card-registration .select-input.form-control[readonly]:not([disabled]) {
            font-size: 1rem;
            line-height: 2.15;
            padding-left: .75em;
            padding-right: .75em;
        }
        .card-registration .select-arrow {
            top: 13px;
        }
    </style>
</head>
<body>
  <?php include 'nav_bar.php'; ?>
    <main class="main" id="top">
        <section class="pt-9 pb-4 bg-light-gradient border-bottom border-white border-5">
            <div class="bg-holder overlay overlay-light" style="background-image:url(assets/img/gallery/header-bg.png);background-size:cover;"></div>

            <div class="container">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-12">
                            <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                                <div class="card-body p-0">
                                    <div class="row g-0">
                                        <div class="col-lg-8">
                                            <div class="p-5"> 
                                                <div class="d-flex justify-content-between align-items-center mb-5">
                                                    <h1 class="fw-bold mb-0">Shopping Cart</h1>
                                                </div>

                                                <hr class="my-4">
                                                <?php foreach ($results as $item): ?>
                                                    <div class="row mb-4 d-flex justify-content-between align-items-center" data-order-item-id="<?php echo $item['order_item_id']; ?>">
                                                        <div class="col-md-2 col-lg-2 col-xl-2">
                                                            <img src="/scentify/public/assets/img/gallery/<?php echo ($item['product_image']); ?>" class="img-fluid rounded-3" alt="<?php echo ($item['product_description']) ?>" > 
                                                        </div>
                                                        <div class="col-md-3 col-lg-3 col-xl-3">
                                                            <h6 class="mb-0"><?php echo $item['product_description'] ?></h6>
                                                        </div>
                                                        <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                                            <input min="1" name="quantity" value="<?php echo $item['total_quantity'] ?>" type="number"
                                                                   class="form-control form-control-sm quantity" 
                                                                   data-unit-price="<?php echo $item['total_price'] / $item['total_quantity']; ?>"
                                                                   data-order-item-id="<?php echo $item['order_item_id']; ?>"
                                                                   onchange="updateQuantity(this)" />
                                                        </div>
                                                        <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                            <h6 class="mb-0 total-price">$<?php echo $item['total_price'] ?></h6>
                                                        </div>
                                                        <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                           <button>delete</button>
                                                        </div>
                                                    </div>
                                                    <hr class="my-4">
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 bg-body-tertiary">
                                            <div class="p-5">
                                                <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                                                <hr class="my-4">
                                                <div class="d-flex justify-content-between mb-4">
                                                    <h5 class="text-uppercase">Items <?php echo count($results); ?></h5>
                                                    <h5 id="cart-total">$<?php echo $totalAmount; ?></h5>
                                                </div>
                                                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-block btn-lg" data-mdb-ripple-color="dark">Checkout</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <section class="py-5"></section>
        </main>
        
        <script src="vendors/@popperjs/popper.min.js"></script>
        <script src="vendors/bootstrap/bootstrap.min.js"></script>
        <script src="vendors/is/is.min.js"></script>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
        <script src="vendors/feather-icons/feather.min.js"></script>
        <script src="assets/js/theme.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
        <script src="/scentify/public/cart.js"></script>
        <script>
            function updateQuantity(input) {
                const newQuantity = input.value;
                const unitPrice = parseFloat(input.dataset.unitPrice);
                const orderItemId = input.dataset.orderItemId;

                // Calculate new total price for this item
                const totalPriceElement = input.closest('.row').querySelector('.total-price');
                const newTotalPrice = (unitPrice * newQuantity).toFixed(2);
                totalPriceElement.textContent = `$${newTotalPrice}`;

                // Send AJAX request to update quantity in the database
                fetch('update_quantity.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ order_item_id: orderItemId, quantity: newQuantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the cart summary total
                        document.getElementById('cart-total').textContent = `$${data.newTotalCartAmount}`;
                    } else {
                        alert('Failed to update quantity');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        </script>
        <?php include 'footer.html'; ?>
    </body>
</html>
