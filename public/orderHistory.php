<?php
include "./includes/include.php";
include "conn.php";

// Dummy orders array for demonstration purposes
$orders = [
    ['id' => '001', 'name' => 'Perfume XYZ', 'quantity' => 2, 'cost' => '323.13', 'date' => '05/31/2021', 'status' => 'Rejected', 'image' => 'https://bootdey.com/img/Content/user_3.jpg'],
    ['id' => '002', 'name' => 'Perfume ABC', 'quantity' => 12, 'cost' => '12623.13', 'date' => '06/12/2021', 'status' => 'Pending', 'image' => 'https://bootdey.com/img/Content/user_1.jpg'],
    ['id' => '003', 'name' => 'Perfume DEF', 'quantity' => 4, 'cost' => '523.13', 'date' => '06/20/2021', 'status' => 'Approved', 'image' => 'https://bootdey.com/img/Content/user_2.jpg']
];

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <?php
    $document = new HTMLDocument("Scentify");
    $document->renderHead();

    $navbar = new Navbar();
    $navbar->render();
    ?>

    <main class="main" id="top">
    <section class='py-11 bg-light-gradient border-bottom border-white border-5'>
        <div class='container bootdey'>
            <div class="panel panel-default panel-order">
                <div class="panel-heading">
                    <strong>Order History</strong>
                </div>

                <div class="panel-body">
                    <?php foreach ($orders as $order): ?>
                    <div class="row">
                        <div class="col-md-1">
                            <img src="<?php echo $order['image']; ?>" class="media-object img-thumbnail" />
                        </div>
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <label class="label <?php echo strtolower($order['status']); ?>">
                                            <?php echo $order['status']; ?>
                                        </label>
                                    </div>
                                    <span><strong>Order ID:</strong> <?php echo $order['id']; ?></span><br>
                                    <span><strong>Order Name:</strong> <?php echo $order['name']; ?></span><br>
                                    <span><strong>Quantity:</strong> <?php echo $order['quantity']; ?></span>, 
                                    <span><strong>Cost:</strong> $<?php echo $order['cost']; ?></span><br>
                                    <span><strong>Order Date:</strong> <?php echo $order['date']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    </main>

    <?php include "footer.html"; ?>

    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script>
    feather.replace();
    </script>
    <script src="assets/js/theme.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <script src="./assets/js/script.js"></script>
</body>
</html>
