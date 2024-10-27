<?php
    class product_card {
        private $product;
    
        public function __construct($product) {
            $this->product = $product;
        }
        
        public function render() {
            echo "<form action='newindex.php' method='get' class='col-sm-6 col-md-4 col-lg-3 mb-3 mb-md-0 h-100'>
                            <input type='hidden' value='{$this->product['product_id']}' name='product_id'>
                            <div class='card card-span h-100 text-white'><img class='img-fluid rounded h-100' src='assets/img/gallery/{$this->product['product_image']}' alt='...'>
                                <div class='ps-0'> </div>
                                <div class='card-body ps-0 pe-0 bg-200'>
                                    <div class='card-description'>
                                        <div class=''>
                                            <h5 class='fw-bold text-1000 text-truncate'>{$this->product['product_name']}</h5>
                                        </div>
                                        <p class='fw-bold mb-2'><span class='text-primary'>\${$this->product['price']}</span>
                                        </p>
                                    </div>
                                    <button type='button' class='btn btn-primary w-100 add-to' data-bs-toggle='modal' data-bs-target='#exampleModal' id='add_item_{$this->product['product_id']}' product_id={$this->product['product_id']}>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-cart' viewBox='0 0 16 16'>
                                            <path d='M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2'>
                                            </path>
                                        </svg> add to cart </button>
                                    <script>
                                        document.getElementById('add_item_{$this->product['product_id']}').addEventListener('click', () => {
                                            quantity_num.value = 1;
                                            add_item_id.value = {$this->product['product_id']};
                                            cart_image.setAttribute('src', 'assets/img/gallery/{$this->product['product_image']}');
                                            add_item_title.innerHTML = '{$this->product['product_name']}';
                                            cart_price.innerHTML = '\${$this->product['price']}';
                                            add_quantity.setAttribute('onclick', 'addbutton({$this->product['stock_quantity']})')
                                            add_quantity.removeAttribute('disabled');
                                            min_quantity.setAttribute('disabled', '');
                                        });
                                        if({$this->product['product_id']} <= 0){
                                                document.getElementById('add_item_{$this->product['product_id']}').classList.add('btn-secondary');
                                            }
                                    </script>
                                </div>
                            </div>
                        </form>";
        }
    }