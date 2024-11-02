<?php
include "./includes/include.php";
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
    
    <?php include "insert_data_form.php" ?>
    <main class="main min-vh-100" id="top">
    <section class='py-4 bg-light-gradient border-bottom border-white border-5'>
    <div class='bg-holder overlay overlay-light'
         style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'>
    </div>
    <div class="mt-8" style="position: relative; z-index: 1;">
        <h1 class='text-center mb-4'><?= htmlspecialchars($_GET['category_name']) ?></h1>
    </div>
    <?php
    $productDisplay = new ProductDisplay();
    $productDisplay->render("SELECT * FROM `products` JOIN `categories` on products.category_id = categories.category_id WHERE category_name = '{$_GET['category_name']}' AND products.is_deleted != 1");
    ?>
    </section>
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
    <?php
        if(isset($_GET["product_id"])){
            echo $_GET["product_id"];
        }
    ?>

    <script src="./assets/js/script.js"> 
        
        
    </script>
</body>
</html>