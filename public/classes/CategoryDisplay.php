<?php
    class CategoryDisplay extends Database {

        public function render() {
            $sql = "SELECT * FROM categories WHERE is_deleted != 1";
            $categories = $this->getConnection()->query($sql);
            echo "<div class='container' id='Categoreys'><div class='row h-100 g-3'>
            <h1>Categories</h1>";
            foreach ($categories as $category) {
                echo "<a class='col-md-4 col-sm-6 block' href='productDisplay_page.php?category_name={$category['category_name']}'>
                        <div class='card card-span h-100 text-white category border-primary1'><img class='card-img h-100' src='assets/img/gallery/{$category['image']}' alt='...'>
                            <div class='card-img-overlay bg-dark-gradient'>
                                <div class='d-flex align-items-end justify-content-center h-100'><span class='btn btn-lg text-light fs-1' role='button'>{$category['category_name']} <svg class='bi bi-arrow-right-short' xmlns='http://www.w3.org/2000/svg' width='23' height='23' fill='currentColor' viewBox='0 0 16 16'>
                                            <path fill-rule='evenodd' d='M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z'> </path>
                                        </svg></span></div>
                            </div>
                        </div>
                    </a>";
            }
            echo "</div></div>";
        }
    }
?>