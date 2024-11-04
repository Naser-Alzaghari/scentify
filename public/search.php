<?php
    if(!isset(($_SESSION))){
        session_start();
    }
    if(isset($_POST["usersearch"])){
        $usersearch = $_POST["usersearch"];
        $_SESSION["usersearch"] = $usersearch;}
    else if(isset($_SESSION["usersearch"]))
        $usersearch = $_SESSION["usersearch"];

    
    try {
        require_once "conn.php";

        // Search query 
        $query = "SELECT * FROM products WHERE product_name LIKE :usersearch AND is_deleted != 1";
        $stmt = $conn->prepare($query);

        $search_term = "%" . $usersearch . "%";
        $stmt->bindParam(":usersearch", $search_term, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

?>

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

    <link href="assets/css/theme.css" rel="stylesheet" />
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

    

    <section class="py-5">
        <div class="container mt-5">
        <h2 class="mb-4">Search Result for "<?=$usersearch?>"</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4">
                <?php
                if (empty($results)) {
                    $image_path = "assets/img/gallery/search-not-found.svg";
                    echo "<div class='no-result container'>";
                    echo '<img src="' . $image_path . '" alt="No results found">';
                    echo "<p>No result found</p>";
                    echo "</div>";
                } else {
                    // Display search results
                    foreach ($results as $row){
                        $obj = new product_card($row);
                        $obj->render();
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <?php include "insert_data_form.php"; include "footer.html"; ?>

    <!-- Scripts -->
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
