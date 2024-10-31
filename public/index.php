<?php
include "./includes/include.php";
include "conn.php";
// Start the session to use session variables


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

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/gallery/title_logo.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/gallery/title_logo.png">
    <meta name="msapplication-TileImage" content="assets/img/gallery/title_logo.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="./assets/css/style.css">

    <style>

        
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
    <main class="main mb-6" id="top">
    <section class='py-11 bg-light-gradient border-bottom border-white border-5'>
        <div class='bg-holder overlay overlay-light'
            style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'>
        </div>
        
        <div class='container'>
            <div class='row flex-center'>
                <div class='col-12 mb-10'>
                    <div class='d-flex align-items-center flex-column'>
                        <h1 class='fw-normal text-center'>Elevate Your Aura with Premium Perfumes</h1>
                        <h1 class='fs-4 fs-lg-8 fs-md-6 fw-bold'>Fragrances That Define You</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="py-0" id="header" style="margin-top: -23rem !important;">
        <div class="container mb-6">
            <div class="row g-4">
                <div class="col-md-6">
                    <a class="card card-span h-100 text-white rounded category border-primary1" href="productDisplay_page.php?category_name=Women"> 
                        <img class="img-fluid rounded" src="assets/img/gallery/women.png" width="590" alt="..." style="aspect-ratio: 1 / 1;" />
                        <div class="card-img-overlay d-flex flex-center"> 
                            <span class="btn btn-lg btn-light">For Her</span>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="card card-span h-100 text-white rounded category border-primary1" href="productDisplay_page.php?category_name=Men"> 
                        <img class="img-fluid rounded" src="assets/img/gallery/men.png" width="590" alt="..." />
                        <div class="card-img-overlay d-flex flex-center"> 
                            <span class="btn btn-lg btn-light">For Him</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Include the insert data form -->
    <?php include "insert_data_form.php" ?>

    <h1 class='text-center mb-4'>Top Selling</h1>

<?php
// Fetch and display the top selling products
$productDisplay = new ProductDisplay();
$products = $conn->query("SELECT * FROM products LIMIT 6" )->fetchAll();


?>
    <?php
    // Display the top selling products
    $productDisplay = new ProductDisplay();
    $productDisplay->render("SELECT * FROM products LIMIT 8");

    // Display the categories
    $categoryDisplay = new CategoryDisplay();
    $categoryDisplay->render();
    ?>
    </main>
    <?php
    include "footer.html";
    ?>

    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script>
    feather.replace();
    </script>
    <script src="assets/js/theme.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">

    <!-- Check if product ID is set in the URL and output it for debugging -->
    <?php
        if(isset($_GET["product_id"])){
            echo "<p>Product ID: " . $_GET["product_id"] . "</p>";
        }
    ?>

    <!-- External custom script -->
    <script src="./assets/js/script.js"></script>
</body>
</html>
