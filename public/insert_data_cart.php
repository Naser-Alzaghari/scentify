<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        session_start();
        $_SESSION['user_id']=1;
        $product_id = $_POST['add_item_id'];
        $quantity = $_POST['quantity'];

        
        
        try {
            include "conn.php";
    
            // get product data from products table
            $query="SELECT * FROM `products` WHERE product_id = :product_id";
            $statment=$conn->prepare($query);
            $statment->bindParam(':product_id',$product_id,PDO::PARAM_INT);
            $statment->execute();
            $product = $statment->fetch(PDO::FETCH_ASSOC);
            print_r($product);
            echo "<br><br><br>";
        
        
            // get last order number and check if cart is empty
            $query="SELECT Max(orders.order_id) as last_order, MAX(on_cart) as cart_not_empty FROM `order_items` JOIN `orders` on order_items.order_id = orders.order_id WHERE user_id = :user_id";
            $statment=$conn->prepare($query);
            $statment->bindParam(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
            $statment->execute();
            $last_order = $statment->fetch(PDO::FETCH_ASSOC);
            print_r($last_order);
        
            // if cart is empty and new order, if not get the last order
            if ($last_order["cart_not_empty"]){
                $order_id=$last_order['last_order'];
                echo "lastorder is $order_id";
            } else {
                $sql ="INSERT INTO `orders`(`user_id`) VALUES (:user_id);";
                $statment=$conn->prepare($sql);
                $statment->bindParam(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
                $statment->execute();
                $sql = "SELECT MAX(order_id) AS last_order FROM orders;";
                $statment=$conn->prepare($sql);
                $statment->execute();
                $last_order = $statment->fetch(PDO::FETCH_ASSOC);
                echo "new order ";
                $order_id=$last_order['last_order'];
                print_r($order_id);
            }
        
            // add item to the cart (last order)
            $sql ="INSERT INTO `order_items`(`order_id`, `product_id`, `quantity`, `price`) VALUES (:order_id,:product_id,:quantity,:price)";
            $statment=$conn->prepare($sql);
            $price = $product['price'] * $quantity;
            $statment->bindParam(':order_id',$order_id,PDO::PARAM_INT);
            $statment->bindParam(':product_id',$product_id,PDO::PARAM_INT);
            $statment->bindParam(':quantity',$quantity,PDO::PARAM_INT);
            $statment->bindParam(':price',$price);
            print_r($statment);
            echo $last_order['last_order']."<br>";
            echo $product_id."<br>";
            echo $quantity."<br>";
            echo $product['price']."<br>";
            $statment->execute();
        
            // calculat total price for order
            $sql ="SELECT SUM(price) as total_amount FROM `order_items` JOIN `orders` on order_items.order_id = orders.order_id WHERE user_id = :user_id and on_cart = 1;";
            $statment=$conn->prepare($sql);
            $statment->bindParam(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
            $statment->execute();
            $total_amount = $statment->fetch(PDO::FETCH_ASSOC);
            echo $total_amount['total_amount'];
        
            // update total price for the order
            $sql = "UPDATE `orders` SET `total_amount`= :total_amount WHERE order_id = :order_id";
            $statment=$conn->prepare($sql);
            $statment->bindParam(':total_amount',$total_amount['total_amount']);
            $statment->bindParam(':order_id',$order_id,PDO::PARAM_INT);
            $statment->execute();
        
            $_SESSION['added_item']=$product["product_name"];
            header("location: index.php");

         
    
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }else{
        header("Location: index.php");
        exit();
    }

    

    ?>
</body>
</html>