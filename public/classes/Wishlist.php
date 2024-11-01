<?php

class Wishlist extends Database {
    private $wishlist_id;
    private $wishlist_items;
    private $wishlist_owner_user_id;
    private $conn;

    public function __construct($user_id) {

        $this->wishlist_id = array();
        $this->wishlist_items = array();

        $conn_obj = new Database();
        $this->conn = $conn_obj->getConnection();
        $this->wishlist_owner_user_id = $user_id;
        if ($user_id > 0) {    
            $this->getUserWishlistID();
            $this->getUserWishlistItems();
        }
    }

    public function getUserWishlistID() {
        $user_id = $this->wishlist_owner_user_id;
        $query = "SELECT wishlist_id FROM wishlists WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            // There is a wishlist for this user.
            $this->wishlist_id = $stmt->fetch(PDO::FETCH_ASSOC)['wishlist_id'];
        } else {
            // No wishlist for this user found -> Create one.
            $this->createWishList();
        }
    }

    public function createWishList(){
        $user_id = $this->wishlist_owner_user_id;
        $query = "INSERT INTO wishlists (user_id, created_at, updated_at) VALUES (:user_id, NOW(), NOW());";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        error_log( print_r(  $this->conn->lastInsertId(), true ) );
        $this->wishlist_id = $this->conn->lastInsertId();
    }

    public function getUserWishlistItems() {
        $wishlist_id = $this->wishlist_id;
        $query = "SELECT wishlist_item_id, product_id FROM wishlist_items WHERE wishlist_id = :wishlist_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":wishlist_id", $wishlist_id);
        $stmt->execute();
        $this->wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWishlistItems(){
        return $this->wishlist_items;
    }
    
    
    public function addItem($product_id){
        $query = "INSERT INTO wishlist_items (wishlist_id, product_id, added_at) VALUES (:wishlist_id, :product_id, NOW())";
        $stmt = $this->conn->prepare($query);

        $wishlist_id = $this->wishlist_id;
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":wishlist_id", $wishlist_id);
        $stmt->execute();
        return true;
    }
    public function removeItem($product_id){
        $query = "DELETE FROM wishlist_items WHERE wishlist_id = :wishlist_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);

        $wishlist_id = $this->wishlist_id;
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":wishlist_id", $wishlist_id);
        $stmt->execute();
        return true;
    }
}