<?php
class ContactMessage {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function saveMessage($fullName, $email, $subject, $message) {
        $sql = "INSERT INTO contact_messages (full_name, email_address, subject, message) VALUES (:full_name, :email_address, :subject, :message)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':email_address', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        return $stmt->execute();
    }

    public function getAllMessages() {
        $sql = "SELECT full_name, message, created_at FROM contact_messages ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
?>
