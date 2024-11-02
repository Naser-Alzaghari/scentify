<?php
    class product_card {
        private $product;
        private $current_user_id;
    
        public function __construct($product) {
            $this->product = $product;
            if (isset($_SESSION['user_id'])) {
                $this->current_user_id = (int) $_SESSION['user_id'];
            } else {
                $this->current_user_id = 0;
            }
        }
        
        public function render() {
            $Wishlist = new Wishlist($this->current_user_id);
            $current_user_wishlist_items = $Wishlist->getWishlistItems();
            $current_user_wishlist_product_ids = array_map(function ($ar) {return $ar['product_id'];}, $current_user_wishlist_items);
            $fill_or_not = in_array($this->product['product_id'], $current_user_wishlist_product_ids) ? "currentColor" : "none";
            echo "
            <form action='newindex.php' method='get' class='col-6 col-md-4 col-lg-3 mb-3 mb-md-0 h-100'>
            <input type='hidden' value='{$this->product['product_id']}' name='product_id'>
                <div class='card card-span text-white p-2 mb-3 animate-card' style='background-color: #EDDFE0 !important;'>
                    <a href='product_page.php?product_id={$this->product['product_id']}' class='text-decoration-none'>
                        <img class='img-fluid rounded w-100 mb-2' src='assets/img/gallery/{$this->product['product_image']}' alt='...' style='height:250px;'>
                    </a>
                    <div class='ps-0'> </div>
                    <div class='card-body p-0 bg-transparent'>
                        <div class='card-description'>
                           <div class='d-flex justify-content-between align-items-center mb-2'>
                            <h5 class='fw-bold text-1000 text-truncate mb-0'>{$this->product['product_name']}</h5>
                            <a class='' onclick='toggleHeart(this, {$this->product['product_id']})'>
                                <svg class='feather feather-heart me-1 text-1000' xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='$fill_or_not' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                    <path d='M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z'></path>
                                </svg>
                            </a>
                        </div>
                            <p class='fw-bold mb-2'><span class='text-dark'>\${$this->product['price']}</span></p>
                        </div>
                        <button type='button' class='btn btn-primary1 w-100 add-to' data-bs-toggle='modal' data-bs-target='#exampleModal' id='add_item_{$this->product['product_id']}' product_id='{$this->product['product_id']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-cart' viewBox='0 0 16 16'>
                                <path d='M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2'>
                                </path>
                            </svg> add to cart
                        </button>
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
                                product_description.innerHTML='{$this->product['product_description']}';
                            });
                            if({$this->product['stock_quantity']} <= 0){
                                document.getElementById('add_item_{$this->product['product_id']}').setAttribute('disabled','');
                                document.getElementById('add_item_{$this->product['product_id']}').innerHTML='SOLD OUT';
                            }
                        </script>
                    </div>
                </div>
            </form>
            <script>
                function toggleHeart(element, current_product_id) {
                    debugger;
                    const heartIcon = element.querySelector('svg');
                    var currentFill = heartIcon.getAttribute('fill');

                    $.ajax({
                        type: 'POST',
                        url: 'wishlist_management.php',
                        data: {
                            product_id: current_product_id,
                            user_id: $this->current_user_id,
                            action: currentFill === 'none'? 'add' : 'remove'
                        },
                        datatype: 'json',
                        success: function (response) {
                            response = JSON.parse(response);
                            console.log(response);
                            debugger;
                            // Handle whatever done from the backend...
                            if (response.status == 'success') {
                                // Toggle between filled and outline
                                heartIcon.setAttribute('fill', currentFill === 'none' ? 'currentColor' : 'none');
                            }
                        }
                    });
                }
            </script>
            <style>
                .animate-card {
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }
                .animate-card:hover {
                    transform: scale(1.05);
                    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
                }
            </style>";
        }
        
    }