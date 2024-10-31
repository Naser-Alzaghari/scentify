<?php
// Fetch product_id from the URL

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} else {
    // If no product_id is passed, redirect or show an error
    echo "No product selected.";
    exit;
}

include "conn.php";
include "./includes/include.php";
// Fetch product details from the database
$query = $conn->prepare("SELECT * FROM products WHERE product_id = :product_id");
$query->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$query->execute();
$product = $query->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Scentify</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="product_page_style.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link href="assets/css/theme.css" rel="stylesheet" />
</head>

<body>
    <main class="main" id="top">
        <?php include "nav_bar.php"; ?>
        
        <!-- Product Section -->
        <section class="py-5">
            <div class="container my-5">
                <div class="row gx-4 gx-lg-5 align-items-center">
                    <div class="col-md-6">
                        <!-- Display product image -->
                        <img class="card-img-top mb-5 mb-md-0" src="assets/img/gallery/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" />
                    </div>
                    <div class="col-md-6">
                        <div class="small mb-1">SKU: <?php echo htmlspecialchars($product['product_image']); ?></div>
                        <!-- Display product name -->
                        <h1 class="display-5 fw-bolder"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                        <div class="fs-5 mb-5">
                            <!-- Display product price -->
                            <span class="text-decoration-line-through">$<?php echo htmlspecialchars($product['price']); ?></span>
                            <span>$<?php echo htmlspecialchars($product['price']); ?></span>
                        </div>
                        <!-- Display product description -->
                        <p class="lead"><?php echo htmlspecialchars($product['product_description']); ?></p>
                        <form action="insert_data.php" method="POST">
                            <div class="d-flex">
                                <input type="hidden" name="add_item_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                
                                <div class="d-inline-flex quantity text-center border border-black rounded me-3" style="width: fit-content;">
                                    <input class="" id="min_quantity" type="button" value="-" disabled>
                                    <input type="num" class="p-2 text-center" name="quantity" id="quantity_num" value="1" style="width:50px" readonly>
                                    <input type="button" value="+" id="add_quantity" onclick="addbutton(<?=$product['stock_quantity']?>)">
                                </div>
                                <button class="btn btn-outline-dark flex-shrink-0" type="submit">
                                    <i class="bi-cart-fill me-1"></i>
                                    Add to cart
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related items section (Optional) -->
        <?php
// Fetch related products from the same category, excluding the current product
$related_query = $conn->prepare("SELECT * FROM products ORDER BY RAND() LIMIT 4");

$related_query->execute();
$related_products = $related_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Related items section -->
<section class="py-5 bg-light">
    <div class="container mt-5">
        <h2 class="fw-bolder mb-4">Related products</h2>
        <div class="row justify-content-center">
        <?php
        $productDisplay = new ProductDisplay();
        $productDisplay->render("SELECT * FROM `products` WHERE stock_quantity > 0 ORDER BY RAND() LIMIT 4");
        ?>
            
        </div>
    </div>
</section>

        
        <?php include "insert_data_form.php"; include "footer.html"; ?>
    </main>

    <!-- Scripts -->
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script>
    feather.replace();
    </script>
    <script src="assets/js/theme.js"></script>
    <script src="./assets/js/script.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
</body>
</html>
