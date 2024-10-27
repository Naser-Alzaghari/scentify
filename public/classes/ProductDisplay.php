<?php
    class ProductDisplay extends Database{

        public function render($sql) {
            $products = $this->getConnection()->query($sql);
            if ($products->rowCount() > 0) {
                echo "<div class='container'>
                    <div class='row'>";
                foreach ($products as $product) {
                    $obj = new product_card($product);
                    $obj->render();
                }
                echo "</div>
                    </div>";
            }
        }
    }
?>