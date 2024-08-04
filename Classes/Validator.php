<?php
class Validator {
    public function validatePassword($password) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password);
    }

    public function validateHealthConditions($data) {
        return $data['hiv'] || $data['heart_disease'] || $data['diabetes'] || $data['fits'] || $data['paralysis'] || $data['lung_diseases'] || $data['liver_diseases'] || $data['kidney_diseases'] || $data['blood_diseases'] || $data['cancer'];
    }
    
    public function validateNIC($donorNIC) {
        if (preg_match('/^\d{9}[vx]$/i', $donorNIC) || preg_match('/^\d{12}$/', $donorNIC)) {
            // NIC is valid according to either format
            return true;
        } else {
            // NIC is not valid according to the specified formats
            return false;
        }
    }

    public function sanitizeInput($input) {
        // Sanitize input data
        return filter_var($input, FILTER_SANITIZE_STRING);
    }

    public function validateUsername($username, $donor) {
        if ($donor->CheckUserName($username)) {
            return "Username '$username' already exists. Please choose a different username. ";
        }
        return "";
    }

    public function validateUniqueNIC($donorNIC, $donor) {
        if ($donor->DonorNICExists($donorNIC)) {
            return "Donor NIC '$donorNIC' already exists. Please use a different NIC. ";
        }
        return "";
    }

    public function validateFileUpload($file_name, $file_tmp) {
        $upload_dir = '../Upload/';
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_destination = $upload_dir . $file_name;
        $error_msg = "";

        if (!in_array($file_ext, $allowed_types)) {
            $error_msg .= "Only JPG, JPEG, PNG, and GIF files are allowed. ";
        }

        if (!move_uploaded_file($file_tmp, $file_destination)) {
            $error_msg .= "Error occurred while uploading the file. ";
        }

        return $error_msg;
    }

    public function validatePasswordMatch($password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            return "Passwords do not match. ";
        }
        return "";
    }

    public function validatePasswordStrength($password) {
        if (!$this->validatePassword($password)) {
            return "Password must contain at least one uppercase letter, one lowercase letter, one symbol, and one number. ";
        }
        return "";
    }

    public function validateHealthConditionsSelection($data) {
        if ($this->validateHealthConditions($data)) {
            return "Sorry, you cannot donate blood due to health conditions. ";
        }
        return "";
    }
}
?>
