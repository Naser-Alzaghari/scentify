<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    echo "product_id: {$_POST['add_item_id']}";
    echo "<br>";
    echo "quantity: {$_POST['quantity']}";
    include "conn.php";
    
    ?>
</body>
</html>