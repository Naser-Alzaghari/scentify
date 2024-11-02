<?php


// Dummy orders array for demonstration purposes
// $orders = [
//     ['id' => '001', 'name' => 'Perfume XYZ', 'quantity' => 2, 'cost' => '323.13', 'date' => '05/31/2021', 'status' => 'Rejected', 'image' => 'https://bootdey.com/img/Content/user_3.jpg'],
//     ['id' => '002', 'name' => 'Perfume ABC', 'quantity' => 12, 'cost' => '12623.13', 'date' => '06/12/2021', 'status' => 'Pending', 'image' => 'https://bootdey.com/img/Content/user_1.jpg'],
//     ['id' => '003', 'name' => 'Perfume DEF', 'quantity' => 4, 'cost' => '523.13', 'date' => '06/20/2021', 'status' => 'Approved', 'image' => 'https://bootdey.com/img/Content/user_2.jpg']
// ];


?>

<?php
include "conn.php";

function getOrderHistory($conn, $user_id) {
    
    $query = "
    SELECT 
        o.order_id, 
        o.user_id, 
        o.total_amount, 
        o.order_status, 
        o.payment_status, 
        o.shipping_address, 
        o.created_at, 
        oi.order_item_id, 
        oi.product_id, 
        oi.quantity, 
        oi.price, 
        oi.on_cart
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.user_id = :user_id AND o.order_status != 'pending' GROUP By o.order_id ORDER BY o.order_id DESC;
";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
   // print_r($orders);

    return $orders;
}



?>