<?php 

session_start();
$user_id = $_SESSION['user_id'];

include ('./includes/include.php');
include('./insert_data_form.php');
$wishlist_obj = new Wishlist($user_id);
$wishlist_items = $wishlist_obj->getWishlistItems();

$current_user_wishlist_product_ids = array_map(function ($ar) {return $ar['product_id'];}, $wishlist_items);

$database = new Database();
$conn = $database->getConnection();

$sql = "SELECT product_id, product_name, product_description, product_image, price FROM products WHERE product_id IN (" . implode(",", $current_user_wishlist_product_ids) . ");";
$stmt = $conn->prepare($sql);
$stmt->execute();
$all_products_in_wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <br>
    <br>
    <br>
 <h1  class =" text-center container">Wishlist</h1>
    
<?php


echo "<br>";
echo "<br>";
echo "<br>";
$productDisplay = new ProductDisplay();
$productDisplay->render("SELECT product_id, product_name, product_description, product_image, price, stock_quantity FROM products WHERE product_id IN (" . implode(",", $current_user_wishlist_product_ids) . ");");


?>




    <?php include 'nav_bar.php'; ?>

    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        
   
        
    <?php include 'footer.html'; ?>
</body>
</html>

