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

$sql = "SELECT stock_quantity, product_id, product_name, product_description, product_image, price FROM products WHERE product_id IN (" . implode(",", $current_user_wishlist_product_ids) . ");";
$stmt = $conn->prepare($sql);
try {
    $stmt->execute();
    $all_products_in_wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/gallery/title_logo.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/gallery/title_logo.png">
    <meta name="msapplication-TileImage" content="assets/img/gallery/title_logo.png">
    <meta name="theme-color" content="#ffffff">

    <link href="assets/css/theme.css" rel="stylesheet" />
    <style>
        body{
            background-color: #f3f3f3 !important;
        }
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

        /* Container styling */
.no-result-container {
   /* margin-left: 300px; */
   /* margin: 0 100px; */

   display: flex!important;
   justify-content: center!important;
   align-items: center !important;
   flex-direction: column;
   margin: 0 auto !important;
    
}

/* Video styling with fixed width and height */
.no-result-video {
    width: 500px; /* Set desired width */
    height: 300px; /* Set desired height */
    border-radius: 10px; /* Optional rounded corners */
    
}

/* Text styling */
.no-result-text {
    margin-top: 15px;
    color: #555;
    font-size: 1.2em;
    font-weight: 500;
}

    
    </style>
</head>
<body >


    </div>
    <?php
    $document = new HTMLDocument("Scentify");
    $document->renderHead();

    $navbar = new Navbar();
    $navbar->render();

    $alert = new Alert();
    $alert->showAlert();
    ?>

    
<div class='bg-light-gradient'>
        <div class='bg-holder overlay overlay-light'
            style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'>
        </div>
    <section class="py-5">
        <div class="container mt-5">
        <h2 class="mb-4 text-center">Wishlist</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4">
                <?php
                if (empty($all_products_in_wishlist)) {
                    $video_path = "assets/video/no-result.mp4"; // Corrected path
                    echo "<div class='no-result-container'>";
                    echo '<img src="./assets/img/gallery/not_found.gif" style="max-width: 300px;">';
                    echo "<p class='no-result-text'>Wishlist is empty</p>";
                    echo "</div>";



                   
                 
                } else {
                    // Display wichlist results
                    foreach ($all_products_in_wishlist as $row){
                        $obj = new product_card($row);
                        $obj->render();
                    }
                }
                ?>
            </div>
        </div>
    </section>
    </section>
    </div>
    <?php include "footer.html"; ?>

    <!-- Scripts -->
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./assets/js/script.js"></script>
    <script>
    feather.replace();
    </script>
        
</body>
</html>
