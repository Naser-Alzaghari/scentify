<?php
    class ProductDisplay extends Database{

        public function render($sql) {
            $products = $this->getConnection()->query($sql);
            if ($products->rowCount() > 0) {
                echo "<div class='container mb-4'>
                    <div class='row'>";
                foreach ($products as $product) {
                    $obj = new product_card($product);
                    $obj->render();
                }
            
                echo "</div>
                    </div>";
            } else {
                echo "<p class='text-center'>No products found</p>";
            }
            echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
        }
    }
?>