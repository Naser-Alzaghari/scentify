<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Scentify</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/gallery/title_logo.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/gallery/title_logo.png">
    <meta name="msapplication-TileImage" content="assets/img/gallery/title_logo.png">
    <meta name="theme-color" content="#ffffff">



    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="assets/css/theme.css" rel="stylesheet" />

</head>


<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <?php include "nav_bar.php" ?>
        <section class="py-11 bg-light-gradient border-bottom border-white border-5">
            <div class="bg-holder overlay overlay-light"
                style="background-image:url(assets/img/gallery/header-bg.png);background-size:cover;">
            </div>
            <!--/.bg-holder-->

            <div class="container">
                <div class="row flex-center">
                    <div class="col-12 mb-10">
                        <div class="d-flex align-items-center flex-column">
                            <h1 class="fw-normal">Elevate Your Aura with Premium Perfumes</h1>
                            <h1 class="fs-4 fs-lg-8 fs-md-6 fw-bold">Fragrances That Define You</h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <section class="py-0" id="header" style="margin-top: -23rem !important;">

            <div class="container">
                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="card card-span h-100 text-white"> <img class="img-fluid"
                                src="assets/img/gallery/her.png" width="790" alt="..." />
                            <div class="card-img-overlay d-flex flex-center"> <a class="btn btn-lg btn-light"
                                    href="#!">For Her</a></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-span h-100 text-white"> <img class="img-fluid"
                                src="assets/img/gallery/him.png" width="790" alt="..." />
                            <div class="card-img-overlay d-flex flex-center"> <a class="btn btn-lg btn-light"
                                    href="#!">For Him </a></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of .container-->

        </section>


        <section>
            <ul class="nav nav-pills justify-content-center mb-5" id="pills-tab-women" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-wtshirt-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-wtshirt" type="button" role="tab" aria-controls="pills-wtshirt"
                        aria-selected="true">T-Shirt</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-dresses-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-dresses" type="button" role="tab" aria-controls="pills-dresses"
                        aria-selected="false">Shirt</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-wshoes-tab" data-bs-toggle="pill" data-bs-target="#pills-wshoes"
                        type="button" role="tab" aria-controls="pills-wshoes" aria-selected="false">Shoes</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-wwatch-tab" data-bs-toggle="pill" data-bs-target="#pills-wwatch"
                        type="button" role="tab" aria-controls="pills-wwatch" aria-selected="false">Watch </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-wsunglasses-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-wsunglasses" type="button" role="tab" aria-controls="pills-wsunglasses"
                        aria-selected="false">Sunglasses </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-wbagpacks-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-wbagpacks" type="button" role="tab" aria-controls="pills-wbagpacks"
                        aria-selected="false">Bagpacks </button>
                </li>
            </ul>
            <div class="container">
            
                <div class="row">
                    
                    <?php
                                include "conn.php";
                                $sql = "SELECT * FROM products";
                                $products = $conn->query($sql);
                                if($products->rowCount()>0){
                                    foreach($products as $product){
                                        echo "<form action='newindex.php' method='get' class='col-sm-6 col-md-4 col-lg-3 mb-3 mb-md-0 h-100'>
                                        <input type='hidden' value='{$product['product_id']}' name='product_id'>
                        <div class='card card-span h-100 text-white'><img class='img-fluid h-100'
                                src='assets/img/gallery/{$product['product_image']}' alt='...'>
                            <div class='ps-0'> </div>
                            <div class='card-body ps-0 pe-0 bg-200'>
                                <div class='d-flex justify-content-between card-description'>
                                    <div class=''>
                                        <h5 class='fw-bold text-1000 text-truncate'>{$product['product_name']}</h5>
                                    </div>
                                    <div class='fw-bold'><span
                                                class='text-600 me-2 text-decoration-line-through'>{$product['price']}</span><span
                                                class='text-primary'>".round((float)($product['price']*0.8),2)."</span>
                                        </div>
                                </div>
                                <button type='button' class='btn btn-primary w-100 add-to' data-bs-toggle='modal' data-bs-target='#exampleModal' id='add_item_{$product['product_id']}' product_id={$product['product_id']}>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor'
                                                class='bi bi-cart' viewBox='0 0 16 16'>
                                                <path
                                                    d='M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2'>
                                                </path>
                                            </svg>
                                            add to cart
                                        </button>
                                        <script>
                                            
                                            document.getElementById('add_item_{$product['product_id']}').addEventListener('click',()=>{
                                            quantity_num.value = 1;
                                            add_item_id.value = {$product['product_id']};
                                            cart_image.setAttribute('src','assets/img/gallery/{$product['product_image']}');
                                            add_item_title.innerHTML =  '{$product['product_name']}';
                                            cart_price.innerHTML = '\${$product['price']}';
                                            add_quantity.setAttribute('onclick','addbutton({$product['stock_quantity']})')
                                            add_quantity.removeAttribute('disabled');
                                            min_quantity.setAttribute('disabled','');
                                        });
                                        </script>
                            </div>
                            
                            
                        </div>
                    </form>";
                                    }
                                }
                            ?>
         <form action="insert_data.php" method="post">
            
        <div class="modal fade p-2" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="margin: auto; top:50%; -ms-transform: translateY(-50%);
  transform: translateY(-50%);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add to cart</h5>
                        <!-- Updated close button for Bootstrap 5 -->
        
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- name -->
                        
                        <div class="row">
                                <img class='img-fluid h-100 display-block col-5' 
                                alt='...' id="cart_image" style="width: 200px; height: 200px;">
                            
                            <div class="col-7">
                                <h4 class="" id="add_item_title" name="add_item_title"></h4>
                                <h4 class='text-primary' id="cart_price"></h4>
                                <div class="" style="padding: 0 auto;">
                                    <div class="d-inline-flex quantity text-center border border-black rounded mt-3" style="width: fit-content;">
                                    <input class="" id="min_quantity" type="button" value="-" disabled>
                                    <input class="p-2 text-center" name="quantity" id="quantity_num" value="1" style="width:50px" readonly>
                                    <input type="button" value="+" id="add_quantity">
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="add_item_id" id="add_item_id">
                    <div class="modal-footer">
                        <!-- Updated buttons for Bootstrap 5 -->
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-secondary" name="add_student" value="Add">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row h-100 g-2 py-1">
        <h1>Catigorys</h1>
            <div class="col-md-4">
              <div class="card card-span h-100 text-white"><img class="card-img h-100" src="assets/img/gallery/vanity-bag.png" alt="...">
                <div class="card-img-overlay bg-dark-gradient">
                  <div class="d-flex align-items-end justify-content-center h-100"><a class="btn btn-lg text-light fs-1" href="#!" role="button">Vanity Bags
                      <svg class="bi bi-arrow-right-short" xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"> </path>
                      </svg></a></div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-span h-100 text-white"><img class="card-img h-100" src="assets/img/gallery/hat.png" alt="...">
                <div class="card-img-overlay bg-dark-gradient">
                  <div class="d-flex align-items-end justify-content-center h-100"><a class="btn btn-lg text-light fs-1" href="#!" role="button">Hats
                      <svg class="bi bi-arrow-right-short" xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"> </path>
                      </svg></a></div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-span h-100 text-white"><img class="card-img h-100" src="assets/img/gallery/BLU.png" alt="...">
                <div class="card-img-overlay bg-dark-gradient">
                  <div class="d-flex align-items-end justify-content-center h-100"><a class="btn btn-lg text-light fs-1" href="#!" role="button">High Heels
                      <svg class="bi bi-arrow-right-short" xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"> </path>
                      </svg></a></div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-span h-100 text-white"><img class="card-img h-100" src="assets/img/gallery/BLU.png" alt="...">
                <div class="card-img-overlay bg-dark-gradient">
                  <div class="d-flex align-items-end justify-content-center h-100"><a class="btn btn-lg text-light fs-1" href="#!" role="button">High Heels
                      <svg class="bi bi-arrow-right-short" xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"> </path>
                      </svg></a></div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-span h-100 text-white"><img class="card-img h-100" src="assets/img/gallery/BLU.png" alt="...">
                <div class="card-img-overlay bg-dark-gradient">
                  <div class="d-flex align-items-end justify-content-center h-100"><a class="btn btn-lg text-light fs-1" href="#!" role="button">High Heels
                      <svg class="bi bi-arrow-right-short" xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"> </path>
                      </svg></a></div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-span h-100 text-white"><img class="card-img h-100" src="assets/img/gallery/BLU.png" alt="...">
                <div class="card-img-overlay bg-dark-gradient">
                  <div class="d-flex align-items-end justify-content-center h-100"><a class="btn btn-lg text-light fs-1" href="#!" role="button">High Heels
                      <svg class="bi bi-arrow-right-short" xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"> </path>
                      </svg></a></div>
                </div>
              </div>
            </div>
          </div>
          
                </div>
            </div>


            
        </section>
        
        <!-- <section> close ============================-->
        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <?php include "footer.html" ?>
        <!-- <section> close ============================-->
        <!-- ============================================-->


    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->




    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script>
    feather.replace();
    </script>
    <script src="assets/js/theme.js"></script>
    <!-- <script>
        const add_cart_image = document.getElementById("cart_image");
        console.log(add_cart_image);
        document.getElementById("cart_image").setAttribute("src","assets/img/gallery/"+sessionStorage.getItem("product_id"));
    </script> -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <?php
        if(isset($_GET["product_id"])){
            echo $_GET["product_id"];
        }
    ?>

    <script>
        const add_quantity = document.getElementById("add_quantity");
        const quantity_num = document.getElementById('quantity_num');
        const add_item_id = document.getElementById('add_item_id');
        const cart_image = document.getElementById('cart_image');
        const add_item_title = document.getElementById('add_item_title');
        const cart_price = document.getElementById('cart_price');
        const min_quantity = document.getElementById("min_quantity");

        function addbutton($num){
            if(++quantity_num.value >= $num){
                add_quantity.setAttribute("disabled","");
            }
            if(quantity_num.value){
                    min_quantity.removeAttribute("disabled");
                }
        }
        // document.getElementById("add_quantity").addEventListener("click",()=>{
        //     document.getElementById("quantity_num").value++;
        //     if(document.getElementById("quantity_num").value){
        //             min_quantity.removeAttribute("disabled");
        //         }
        // });
        min_quantity.addEventListener("click",()=>{
                if(--quantity_num.value < 2){
                    min_quantity.setAttribute("disabled","");
                }
                document.getElementById("add_quantity").removeAttribute("disabled");
                
                
            
            
        });

        
    </script>
</body>

</html>