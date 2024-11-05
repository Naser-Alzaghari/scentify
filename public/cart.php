

<?php
session_start();
include 'conn.php';

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
$length = count($results);

$totalAmount = array_sum(array_column($results, 'total_price'));
$query_user = "SELECT   `first_name`,`last_name`,`email`,`phone_number`,`address`  FROM `users` WHERE `user_id`=:user_id ;";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bindParam('user_id', $user_id);
$stmt_user->execute();
$user_checkout = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Initialize an empty associative array
$productQuantities = array();

// Loop through the results to fill the associative array
foreach ($results as $result) {
    $productId = $result['product_id']; // Get the product ID
    $quantity = $result['total_quantity']; // Get the quantity

    // Assign the quantity to the associative array using the product ID as the key
    $productQuantities[$productId] = $quantity;
}

// Print the associative array to see the result
$productQuantitiesJson = json_encode($productQuantities);

?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart</title>
    <link href="assets/css/theme.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

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
            <div class='bg-holder overlay overlay-light'
                style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'>
            </div>
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
                                            <?php if ($results != []): ?>
                                                <hr class="my-4">
                                                <?php foreach ($results as $item): ?>
                                                    <div class="row mb-4 d-flex justify-content-between align-items-center" data-order-item-id="<?php echo $item['order_item_id']; ?>">
                                                        <div class="col-md-2 col-lg-2 col-xl-2">
                                                            <img src="./assets/img/gallery/<?php echo ($item['product_image']); ?>" class="img-fluid rounded-3" alt="<?php echo ($item['product_description']) ?>">
                                                        </div>
                                                        <div class="col-md-3 col-lg-3 col-xl-3">
                                                            <h6 class="mb-0"><?php echo $item['product_description'] ?></h6>
                                                        </div>
                                                        <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                                            <input min="1" name="quantity" value="<?php echo $item['total_quantity'] ?>" type="number"
                                                                class="form-control form-control-sm quantity"
                                                                data-unit-price="<?php echo $item['total_price'] / $item['total_quantity']; ?>"
                                                                data-order-item-id="<?php echo $item['order_item_id']; ?>"
                                                                data-order-id="<?= $item['order_id'] ?>"
                                                                onchange="updateQuantity(this)" />
                                                        </div>
                                                        <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                            <h6 class="mb-0 total-price">$<?php echo $item['total_price'] ?></h6>
                                                            <input type="hidden" name="newTotalPrice" value="<?php echo $item['total_price'] ?>">
                                                        </div>
                                                        <div class="col-md-1 col-lg-1 col-xl-1 text-end">

                                                            <button onclick="deleteItem(<?php echo $item['order_item_id']; ?>)" style="background: none; border: none; padding: 0; cursor: pointer;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                                </svg>
                                                            </button>

                                                        </div>
                                                        <hr class="my-4">
                                                    </div>

                                                <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 bg-body-tertiary">
                                        <div class="p-5">
                                            <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                                            <hr class="my-4">
                                            <div class="d-flex justify-content-between mb-4">
                                                <h5 class="text-uppercase">Items <?php echo count($results); ?></h5>
                                                <h5 id="cart-total"><?php echo  "$" . $totalAmount; ?></h5>
                                            </div>
                                            <p class="text-danger d-none" id="stock_message">you exeeded stock limit</p>

                                            <form action="chekoutPage.php" method="POST">
                                                <input type="hidden" name="order_id" value="<?= $results[0]['order_id'] ?>">
                                               <input type="hidden" name="length_of_order"  value="<?= $length ?>">
                                               <input type="hidden" name="total_quantity" value="<?=$totalAmount?>">
                                               <input type="hidden" name="total_quantity" value='<?php echo $productQuantitiesJson; ?>'>
                                                <button type="submit" class="btn btn-primary1 btn-block btn-lg w-100 rounded">
                                                    Proceed
                                                </button>
                                            </form>


                                        </div>
                                    </div>
                                <?php else: ?>
                                    <h1 class="">Cart is empty</h1>
                                <?php endif; ?>
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

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script>
        // script name
        var total_amount_global = <?= $totalAmount ?>;

        function updateQuantity(input) {
            const newQuantity = input.value;
            const unitPrice = parseFloat(input.dataset.unitPrice);
            const orderItemId = input.dataset.orderItemId;
            const order_id = input.dataset.orderId; //order id             

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
                    body: JSON.stringify({
                        order_item_id: orderItemId,
                        quantity: newQuantity,
                        order_id: order_id,
                        newTotalPrice: newTotalPrice
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('cart-total').textContent = `$${parseFloat(data.newTotalCartAmount).toFixed(2)}`;
                        total_amount_global = parseFloat(data.newTotalCartAmount);

                    } else {
                        alert('Failed to update quantity');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

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
    </script>

    <script>
        function deleteItem(orderItemId) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-dark",
                    cancelButton: "btn btn-info"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, remove it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true,
                willOpen: () => {
                    
                    // Add space between buttons
                    const swalActions = document.querySelector('.swal2-actions');
                    if (swalActions) swalActions.style.gap = '10px';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                
                    // Send AJAX request to delete the item from the database
                    // Proceed with deletion if confirmed
                    
                    fetch('delete_item.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                order_item_id: orderItemId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the item row from the cart
                                const itemRow = document.querySelector(`[data-order-item-id="${orderItemId}"]`);
                                if (itemRow) itemRow.remove();

                                // Update the total amount in the cart
                                document.getElementById('cart-total').textContent = `$${data.newTotalCartAmount}`;


                                let cart_total = document.getElementById('cart-total');
                                let newTotal = parseFloat(data.newTotalCartAmount);
                                total_amount_global = newTotal;
                                if (!isNaN(newTotal) && newTotal > 0) {
                                    // If the new total is a valid number and greater than 0
                                    cart_total.textContent = `$${newTotal.toFixed(2)}`;
                                } else {
                                    // If the new total is NaN or 0
                                    location.reload();
                                }

                            } else {
                                // Handle failure to delete
                                Swal.fire("Error", "Failed to remove the item. Please try again.", "error");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            Swal.fire("Error", "An error occurred. Check the console for details.", "error");
                            location.reload();
                        });
                }
            });
        }
        if("<?php if(isset($_SESSION['stock_limit'])){echo $_SESSION['stock_limit'];}else{echo "";} ?>" == "stock exeed limit"){
            document.getElementById("stock_message").classList.remove("d-none");
        }
        <?php unset($_SESSION['stock_limit'])?>
    </script>

    <?php include 'footer.html'; ?>
</body>

</html>
