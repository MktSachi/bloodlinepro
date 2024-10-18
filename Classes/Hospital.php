<?php
class Hospital {
    private $conn;
    private $table = 'hospitals';

    public $hospitalID;
    public $hospitalName;
    public $address;
    public $phoneNumber;
    public $email;

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to add a new hospital
    public function addHospital() {
        $query = "INSERT INTO " . $this->table . " (hospitalName, address, phoneNumber, email) VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->hospitalName = htmlspecialchars(strip_tags($this->hospitalName));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->phoneNumber = htmlspecialchars(strip_tags($this->phoneNumber));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Bind parameters
        $stmt->bind_param("ssss", $this->hospitalName, $this->address, $this->phoneNumber, $this->email);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getHospitals() {
        $query = "SELECT * FROM " . $this->table;
        $result = $this->conn->query($query);
        return $result;
    }
}
?>
