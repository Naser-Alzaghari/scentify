<?php


// Dummy orders array for demonstration purposes
// $orders = [
//     ['id' => '001', 'name' => 'Perfume XYZ', 'quantity' => 2, 'cost' => '323.13', 'date' => '05/31/2021', 'status' => 'Rejected', 'image' => 'https://bootdey.com/img/Content/user_3.jpg'],
//     ['id' => '002', 'name' => 'Perfume ABC', 'quantity' => 12, 'cost' => '12623.13', 'date' => '06/12/2021', 'status' => 'Pending', 'image' => 'https://bootdey.com/img/Content/user_1.jpg'],
//     ['id' => '003', 'name' => 'Perfume DEF', 'quantity' => 4, 'cost' => '523.13', 'date' => '06/20/2021', 'status' => 'Approved', 'image' => 'https://bootdey.com/img/Content/user_2.jpg']
// ];

function getOrderHistory($db, $user_id) {
    $query = "SELECT order_id, total_amount, order_status, payment_status, shipping_address, created_at 
              FROM orders 
              WHERE user_id = :user_id AND is_deleted = 0 
              ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


