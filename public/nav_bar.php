<?php
if(!isset($_SESSION)){
  session_start();
}
include "conn.php";
if(isset($_SESSION['user_id'])){
  $user_id=$_SESSION['user_id'];
  $sql = "SELECT COUNT('on_cart') as count FROM `order_items` JOIN `orders` on order_items.order_id = orders.order_id WHERE user_id = :user_id and on_cart = 1";
  $statment = $conn->prepare($sql);
  $statment->bindParam(':user_id',$user_id);
  $statment->execute();
  $cart_count = $statment->fetch(PDO::FETCH_ASSOC);
} else {
  $cart_count['count']=0;
}

?> <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
    <div class="container"><a class="navbar-brand d-inline-flex" href="index.php"><img class="d-inline-block" src="assets/img/gallery/scentify-high-resolution-logo-transparent.svg" alt="logo" /></a>
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="productDisplay_page.php?category_name=Women">Women</a></li>
                <li class="nav-item px-2"><a class="nav-link fw-medium" href="productDisplay_page.php?category_name=Men">Men</a></li>
                <li class="nav-item px-2"><a class="nav-link fw-medium" href="index.php#Categoreys">Categories</a></li>
            </ul>
            <form class="d-flex align-items-center" action="search.php" method="post">
          <?php

          ?>
          <input class="px-3" id ="search" type="text" name ="usersearch" placeholder="Search a product">
            <form class="d-flex align-items-center"> <?php
            
          ?> <a type="button" href="LoginPage.php" class="btn btn-primary1 me-3 d-none" id="login_button">login</a>
                <a class="text-1000" href="#!">
                    <div style="position: relative;">
                        <a class="text-1000" href="cart.php">
                            <svg class="feather feather-shopping-cart me-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </a>
                </a><a class="text-1000" href="#!">
                    <a class="d-flex justify-content-center align-items-center" href="cart.php" id="cart_number" style="position: absolute; bottom:0; left:0; background-color: red; border-radius:50%; color: white; font-size:6px; width: 10px; aspect-ratio: 1 / 1;"><b><?=$cart_count['count']?></b></a>
        </div>
        </a>
        <div class="dropdown d-none" id="profile_icon">
            <a href="#" class="pe-auto text-1000" data-bs-toggle="dropdown" aria-expanded="false">
                <svg class="feather feather-user me-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="userProfile.php">profile</a></li>
                <li><a class="dropdown-item" href="logout.php">logout</a></li>
            </ul>
        </div>
        <a class="text-1000" href="<?php if(isset($_SESSION["user_id"])){echo "wishlist.php";}else{echo "LoginPage.php";} ?>">
            <svg class="feather feather-heart" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg></a></form>
    </div>
    </div>
</nav> <?php
      if(isset($_SESSION["user_id"])){
        echo "<script>
          document.getElementById('profile_icon').classList.remove('d-none');
          document.getElementById('login_button').classList.add('d-none');
        </script>";
      } else {
        echo "<script>
          document.getElementById('profile_icon').classList.add('d-none');
          document.getElementById('login_button').classList.remove('d-none');
        </script>";
      }
      
    ?> <?php
    

    ?> <script>
    let cart_number = document.getElementById("cart_number");
    let items_number = document.querySelector("#cart_number b");
    if (items_number.innerHTML == 0) {
        cart_number.classList.add("d-none");
    }
</script>