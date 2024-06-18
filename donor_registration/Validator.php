<?php
class Validator {
    public function validatePassword($password) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password);
    }

    public function validateHealthConditions($data) {
        return $data['hiv'] || $data['heart_disease'] || $data['diabetes'] || $data['fits'] || $data['paralysis'] || $data['lung_diseases'] || $data['liver_diseases'] || $data['kidney_diseases'] || $data['blood_diseases'] || $data['cancer'];
    }
}

?>