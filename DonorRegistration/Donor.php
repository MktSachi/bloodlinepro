<?php
include_once 'Database.php';

class Donor {
    private $db;
    private $donorsTable = "donors";
    private $usersTable = "users";

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function CheckUserName($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function DonorNICExists($donorNIC) {
        $sql = "SELECT * FROM donors WHERE donorNIC = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $donorNIC);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function register($data, $profile_picture_path) {
        $this->db->startTransaction();

        try {
            // Insert into users table
            $sql_users = "INSERT INTO users (username, password, roleID, active) VALUES (?, ?, 'donor', true)";
            $stmt_users = $this->db->prepare($sql_users);
            $stmt_users->bind_param("ss", $data['username'], $data['password_hashed']);
            if (!$stmt_users->execute()) {
                throw new Exception("Failed to insert into users table: " . $stmt_users->error);
            }
            $userid = $stmt_users->insert_id;

            // Insert into donors table
            $sql_donors = "INSERT INTO donors (userid, username, first_name, last_name, donorNIC, email, phoneNumber, address, address2, gender, bloodType, hiv, heart_disease, diabetes, fits, paralysis, lung_diseases, liver_diseases, kidney_diseases, blood_diseases, cancer, other_health_conditions, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_donors = $this->db->prepare($sql_donors);
            $stmt_donors->bind_param("issssssssssssssssssssss", $userid, $data['username'], $data['firstName'], $data['lastName'], $data['donorNIC'], $data['email'], $data['phoneNumber'], $data['address'], $data['address2'], $data['gender'], $data['bloodType'], $data['hiv'], $data['heart_disease'], $data['diabetes'], $data['fits'], $data['paralysis'], $data['lung_diseases'], $data['liver_diseases'], $data['kidney_diseases'], $data['blood_diseases'], $data['cancer'], $data['otherHealthConditions'], $profile_picture_path);
            if (!$stmt_donors->execute()) {
                throw new Exception("Failed to insert into donors table: " . $stmt_donors->error);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getUserIDByDonorNIC($donorNIC) {
        $sql = "SELECT userid FROM " . $this->donorsTable . " WHERE donorNIC = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $donorNIC);
        $stmt->execute();
        $stmt->bind_result($userid);
        $stmt->fetch();
        $stmt->close();
    
        return $userid;
    }
    
    public function getDonorDetailsByNIC($donorNIC) {
    $sql = "SELECT first_name, last_name, bloodType, email, phoneNumber, username, address, address2, gender, donation_count FROM donors WHERE donorNIC = ?";
    $stmt = $this->db->prepare($sql);

    if (!$stmt) {
        // Handle prepare error
        error_log("Prepare failed: (" . $this->db->getConnection()->errno . ") " . $this->db->getConnection()->error);
        return false;
    }

    $stmt->bind_param("s", $donorNIC);
    if (!$stmt->execute()) {
        // Handle execute error
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}


    public function deleteDonorByNIC($donorNIC) {
        // Get the user ID associated with the donor
        $query = "SELECT userid FROM " . $this->donorsTable . " WHERE donorNIC = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $donorNIC);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $userId = $user['userid'];

        // Start a transaction
        $this->db->startTransaction();

        try {
            // Delete the donor record
            $query = "DELETE FROM " . $this->donorsTable . " WHERE donorNIC = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $donorNIC);
            $stmt->execute();

            // Delete the associated user record
            $query = "DELETE FROM " . $this->usersTable . " WHERE userid = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();

            // Commit the transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback the transaction if something goes wrong
            $this->db->rollback();
            return false;
        }
 
    }

    public function updateDonorProfile($donorNIC, $username, $email, $phoneNumber, $address, $address2, $profile_picture) {
        $this->db->startTransaction();
    
        try {
            // Handle profile picture upload if a new picture is provided
            $profile_picture_path = null;
            if ($profile_picture['error'] == UPLOAD_ERR_OK) {
                $target_dir = "../Upload/";
                $target_file = $target_dir . basename($profile_picture["name"]);
                if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
                    $profile_picture_path = $target_file;
    
                    // Update profile_picture in donors table
                    $sql_update_picture = "UPDATE donors SET profile_picture = ? WHERE donorNIC = ?";
                    $stmt_update_picture = $this->db->prepare($sql_update_picture);
                    $stmt_update_picture->bind_param("ss", $profile_picture_path, $donorNIC);
                    if (!$stmt_update_picture->execute()) {
                        throw new Exception("Failed to update profile picture: " . $stmt_update_picture->error);
                    }
                } else {
                    throw new Exception("Failed to upload profile picture.");
                }
            }
    
            // Check if new username exists (if changed)
            if ($this->CheckUserName($username)) {
                throw new Exception("Username '$username' already exists. Please choose a different username.");
            }
    
            // Update users table
            $sql_update_user = "UPDATE users SET username = ?, email = ? WHERE userid = (SELECT userid FROM donors WHERE donorNIC = ?)";
            $stmt_update_user = $this->db->prepare($sql_update_user);
            $stmt_update_user->bind_param("sss", $username, $email, $donorNIC);
            if (!$stmt_update_user->execute()) {
                throw new Exception("Failed to update users table: " . $stmt_update_user->error);
            }
    
            // Update other donor details in donors table
            $sql_update_donors = "UPDATE donors SET phoneNumber = ?, address = ?, address2 = ? WHERE donorNIC = ?";
            $stmt_update_donors = $this->db->prepare($sql_update_donors);
            $stmt_update_donors->bind_param("ssss", $phoneNumber, $address, $address2, $donorNIC);
            if (!$stmt_update_donors->execute()) {
                throw new Exception("Failed to update donors table: " . $stmt_update_donors->error);
            }
    
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    
    }

?>
