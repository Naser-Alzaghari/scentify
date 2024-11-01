<?php

require 'classes\Database.php';
require 'classes\Wishlist.php';

$product_id     = $_POST['product_id'];
$user_id        = $_POST['user_id'];
$action         = $_POST['action'];

$wishlist_obj = new Wishlist($user_id);

switch ($action) {
    case 'add':
        if ($wishlist_obj->addItem($product_id)) {
            $response_arr['status'] = 'success';
            $response_arr['message'] = 'Product added to wishlist successfully!';
            echo json_encode($response_arr);
        }
        break;
    case 'remove':
        if ($wishlist_obj->removeItem($product_id)) {
            $response_arr['status'] = 'success';
            $response_arr['message'] = 'Product Deleted From wishlist successfully!';
            echo json_encode($response_arr);
        }
        break;
    default: 
        break;
}
exit();