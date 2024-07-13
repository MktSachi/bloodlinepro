<?php

class Password {
    private $minLength;
    private $hasUppercase;
    private $hasLowercase;
    private $hasNumbers;
    private $hasSpecialChars;

    public function __construct($minLength = 8, $hasUppercase = true, $hasLowercase = true, $hasNumbers = true, $hasSpecialChars = true) {
        $this->minLength = $minLength;
        $this->hasUppercase = $hasUppercase;
        $this->hasLowercase = $hasLowercase;
        $this->hasNumbers = $hasNumbers;
        $this->hasSpecialChars = $hasSpecialChars;
    }

    public function generateRandomPassword($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }

    public function validatePasswordComplexity($password) {
        if (strlen($password) < $this->minLength) {
            return false;
        }
        if ($this->hasUppercase && !preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if ($this->hasLowercase && !preg_match('/[a-z]/', $password)) {
            return false;
        }
        if ($this->hasNumbers && !preg_match('/\d/', $password)) {
            return false;
        }
        if ($this->hasSpecialChars && !preg_match('/[\W]/', $password)) {
            return false;
        }
        return true;
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
?>
