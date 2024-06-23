<?php
class Donor {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function isUsernameExists($username) {
        $sql = "SELECT * FROM donors WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function isDonorNICExists($donorNIC) {
        $sql = "SELECT * FROM donors WHERE donorNIC = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $donorNIC);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function register($data, $profile_picture_path) {
        $sql = "INSERT INTO donors (first_name, last_name, donorNIC, username, email, password, phoneNumber, address, address2, gender, bloodType, hiv, heart_disease, diabetes, fits, paralysis, lung_diseases, liver_diseases, kidney_diseases, blood_diseases, cancer, other_health_conditions, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssssssssssssssssssss", $data['firstName'], $data['lastName'], $data['donorNIC'], $data['username'], $data['email'], $data['password_hashed'], $data['phoneNumber'], $data['address'], $data['address2'], $data['gender'], $data['bloodType'], $data['hiv'], $data['heart_disease'], $data['diabetes'], $data['fits'], $data['paralysis'], $data['lung_diseases'], $data['liver_diseases'], $data['kidney_diseases'], $data['blood_diseases'], $data['cancer'], $data['otherHealthConditions'], $profile_picture_path);
        return $stmt->execute();
    }

    public function getUserByUsername($username) {
        $sql = "SELECT * FROM donors WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    public function getDonorDetailsByNIC($donorNIC) {
    $sql = "SELECT first_name, last_name, bloodType, email, phoneNumber, username, address, address2, gender FROM donors WHERE donorNIC = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $donorNIC);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

public function deleteDonorByNIC($donorNIC) {
    $sql = "DELETE FROM donors WHERE donorNIC = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $donorNIC);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
}
?>