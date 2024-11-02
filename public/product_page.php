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
    <link href="assets/css/style.css" rel="stylesheet" />
    
</head>

<body>
<?php 
        $navbar = new Navbar();
        $navbar->render();

        $alert = new Alert();
        $alert->showAlert();
    ?>
    <main class="main" id="top">
    
    <section class='py-4 bg-light-gradient border-bottom border-white border-5'>
    <div class='bg-holder overlay overlay-light'
         style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'>
    </div>
        <!-- Product Section -->
        <section class="py-5">
            <div class="container my-5">
                <div class="row gx-4 gx-lg-5 align-items-center">
                    <div class="col-md-6">
                        <!-- Display product image -->
                        <img class="card-img-top mb-md-0 img-fluid" style="border: 5px solid #B7B7B7; border-radius: 5px;" src="assets/img/gallery/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" />
                    </div>
                    <div class="col-md-6">
                        
                        <!-- Display product name -->
                        <h1 class="display-5 fw-bolder"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                        <div class="fs-5 mb-5">
                            <!-- Display product price -->
                            
                            <span>$<?php echo htmlspecialchars($product['price']); ?></span>
                        </div>
                        <!-- Display product description -->
                        <p class="lead mb-6"><?php echo htmlspecialchars($product['product_description']); ?></p>
                        <form action="insert_data.php" method="POST">
                            <div class="d-flex">
                                <input type="hidden" name="add_item_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                
                                <div class="d-inline-flex quantity text-center border border-black rounded me-3" style="width: fit-content;">
                                    <input class="" id="min_quantity" type="button" value="-" disabled>
                                    <input type="num" class="p-2 text-center" name="quantity" id="quantity_num" value="1" style="width:50px" readonly>
                                    <input type="button" value="+" id="add_quantity" onclick="addbutton(<?=$product['stock_quantity']?>)">
                                </div>
                                <button type='submit' class='btn w-100 add-to' style='background-color: #705C53; color: #F5F5F7;' id="add_item_<?=$product['product_id']?>">
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-cart' viewBox='0 0 16 16'>
                                <path d='M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2'>
                                </path>
                            </svg> add to cart
                        </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related items section (Optional) -->
        <?php


?>

<!-- Related items section -->
</section>
    <div class="container mt-5">
        <h2 class="fw-bolder mb-4">Related products</h2>
        
        <div class="row justify-content-center">
        <?php
        $productDisplay = new ProductDisplay();
        $productDisplay->render("SELECT * FROM products Where category_id = {$product['category_id']} AND stock_quantity > 0 AND product_id != {$product['product_id']} AND products.is_deleted != 1 ORDER BY RAND() LIMIT 4");
        ?>
            
        </div>
    </div>


        
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
    <script>
                            document.getElementById('add_item_<?php echo htmlspecialchars($product['product_id']); ?>').addEventListener('click', () => {
                                
                                add_item_id.value = <?php echo htmlspecialchars($product['product_id']); ?>;
                                // cart_image.setAttribute('src', 'assets/img/gallery/{$this->product['product_image']}');
                                // add_item_title.innerHTML = '{$this->product['product_name']}';
                                // cart_price.innerHTML = '\${$this->product['price']}';
                                // add_quantity.setAttribute('onclick', 'addbutton({$this->product['stock_quantity']})')
                                // add_quantity.removeAttribute('disabled');
                                // min_quantity.setAttribute('disabled', '');
                                // product_description.innerHTML='{$this->product['product_description']}';
                            });
                            
                            if(<?php echo htmlspecialchars($product['stock_quantity']); ?> <= 0){
                                document.getElementById('add_item_<?php echo htmlspecialchars($product['product_id']); ?>').setAttribute('disabled','');
                                document.getElementById('add_item_<?php echo htmlspecialchars($product['product_id']); ?>').innerHTML='SOLD OUT';
                                document.querySelector('.quantity').classList.add('d-none');
                            }
                        </script>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
</body>
</html>
