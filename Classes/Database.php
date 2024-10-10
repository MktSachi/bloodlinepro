<?php
class Database {
    private $conn;
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbname = 'bloodlinepro_';

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function startTransaction() {
        $this->conn->autocommit(false); // Disable autocommit
    }

    public function commit() {
        $this->conn->commit(); // Commit transaction
        $this->conn->autocommit(true); // Enable autocommit
    }

    public function rollback() {
        $this->conn->rollback(); // Rollback transaction
        $this->conn->autocommit(true); // Enable autocommit
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    public function execute($stmt) {
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        return $stmt->get_result();
    }

    public function update($sql, $params) {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
        }
        if (!$stmt->bind_param(...$params)) {
            throw new Exception("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        return $this->execute($stmt);
    }

    public function close() {
        $this->conn->close();
    }
}
?>
