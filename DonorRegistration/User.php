<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function getUserById($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE userid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updatePassword($user_id, $newPasswordHash) {
        $query = "UPDATE " . $this->table_name . " SET password = ? WHERE userid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $newPasswordHash, $user_id);
        return $stmt->execute();
    }
}
?>
