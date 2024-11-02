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
    
    <!-- <div class="mt-6 rounded w-80 container">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner rounded border-primary1">
        <div class="carousel-item active">
          <img src="./assets/img/gallery/slider.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="./assets/img/gallery/slider1.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="./assets/img/gallery/slider2.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="./assets/img/gallery/slider3.png" class="d-block w-100" alt="...">
        </div>
      </div>
    </div>
    </div> -->
    <section class='py-4 bg-light-gradient border-bottom border-white border-5' style="padding-bottom: 23rem !important;">
        <div class='bg-holder overlay overlay-light'
            style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'>
        </div>
        
        
    
    <div id="carouselExampleControlsNoTouching" class="carousel slide container mt-8 rounded" data-bs-touch="false">
  <div class="carousel-inner">
  <div class="carousel-item position-relative active">
    <div><img src="./assets/img/gallery/perfume.jpg" class="d-block w-100 rounded" style="object-fit: cover;" alt="..."></div>
    <div class="position-absolute ms-6 rounded p-3" style="top: 50%; text-shadow: 3px 3px 5px grey;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%); background-color: rgba(0,0,0,.4);" >
        <p class="fs-4 fs-lg-8 fs-md-6 fw-bold text-white">Enjoy our 20% sales</p>
        <h1 class="fw-normal text-white">Use code: <b>20ORNG</b></h1>
        <a class="btn btn-light mt-4" href="#Top_Selling">Shop Now</a>
    </div>
    </div>
    <div class="carousel-item">
    <a href="productDisplay_page.php?category_name=Packages">
        <img src="./assets/img/gallery/slider2.png" class="d-block w-100 rounded" alt="...">
    </a>
    </div>
    
    <div class="carousel-item">
        <a href="productDisplay_page.php?category_name=Packages"><img src="./assets/img/gallery/slider1.png" class="d-block w-100 rounded" alt="..."></a>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
</section>
    <section class="py-0 mt-6" id="header" style="margin-top: -22rem !important;">
        <div class="container mb-6">
            <div class="row g-4">
                <div class="col-md-6">
                    <a class="card card-span h-100 text-white rounded category border-primary1" href="productDisplay_page.php?category_name=Women"> 
                        <img class="img-fluid rounded w-100" src="assets/img/gallery/women.png" width="590" alt="..." style="aspect-ratio: 1 / 1;" />
                        <div class="card-img-overlay d-flex flex-center"> 
                            <span class="btn btn-lg btn-light">For Her</span>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="card card-span h-100 text-white rounded category border-primary1" href="productDisplay_page.php?category_name=Men"> 
                        <img class="img-fluid rounded w-100" src="assets/img/gallery/men.png" width="590" alt="..." />
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

    <h1 class='text-center mb-4' id="Top_Selling">Top Selling</h1>

<?php
// Fetch and display the top selling products


?>
    <?php
    // Display the top selling products
    $productDisplay = new ProductDisplay();
    $productDisplay->render("SELECT order_items.product_id, `product_name`, `product_description`, `product_image`, products.price, `stock_quantity`, `category_id`, `updated_at`, products.is_deleted , sum(order_items.quantity) as q FROM `order_items` JOIN `products` on order_items.product_id = products.product_id JOIN `orders` on orders.order_id = order_items.order_id WHERE order_status = 'processing' GROUP By product_id ORDER by q DESC LIMIT 8");

    ?>

    <div class="container mb-4">
        <div class="d-flex flex-column justify-content-between align-items-center flex-lg-row gap-3">
            <div><img src=".\assets\img\gallery\free_delivery.png" alt="offers" style="height: 70px"></div>
            <div><img src=".\assets\img\gallery\original.png" alt="offers" style="height: 70px"></div>
            <div><img src=".\assets\img\gallery\best_offers.png" alt="offers" style="height: 70px"></div>
        </div>
    </div>

    <?php
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
