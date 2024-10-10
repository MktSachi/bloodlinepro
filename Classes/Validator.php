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
    $messages = [];

    if ($data['hiv']) {
        $messages[] = "Due to HIV, you are ineligible for blood donation. Focus on boosting your immune system with a balanced diet rich in fruits, vegetables, lean proteins, and whole grains. Regularly take prescribed antiretroviral medications and attend routine check-ups. Avoid smoking and excessive alcohol consumption. Stay active with moderate exercise, and ensure adequate sleep to support your overall health.";
    }
    if ($data['heart_disease']) {
        $messages[] = "Heart disease disqualifies you from blood donation for safety reasons. Prioritize heart-healthy foods like whole grains, leafy greens, and low-fat dairy. Reduce sodium, unhealthy fats, and processed foods. Stay physically active with light to moderate exercises as advised by your doctor. Manage stress, get adequate sleep, and take prescribed medications regularly. Regular check-ups with your cardiologist are important.";
    }
    if ($data['diabetes']) {
        $messages[] = "Uncontrolled diabetes prevents you from donating blood. Manage your blood sugar levels through a balanced diet with whole grains, lean proteins, and fiber-rich vegetables. Avoid sugary foods and beverages. Regular exercise is important for glucose control—aim for at least 30 minutes of physical activity a day. Monitor your blood sugar and follow your doctor's advice on medications and insulin management.";
    }
    if ($data['fits']) {
        $messages[] = "Fits (Epilepsy) makes you ineligible for blood donation. Focus on a diet rich in omega-3s, such as fish, flaxseeds, and walnuts, to support brain health. Maintain a regular sleep schedule, avoid known seizure triggers, and take prescribed medications. Moderate physical activity can help manage stress, but always consult your neurologist before making any major lifestyle changes.";
    }
    if ($data['paralysis']) {
        $messages[] = "Paralysis prevents blood donation. Focus on maintaining a healthy, well-balanced diet with lean proteins, fruits, and vegetables to avoid other health complications. Engage in physical therapy exercises as recommended by your healthcare provider to maintain mobility and strength. Regular medical check-ups are essential to monitor your condition and prevent secondary issues like infections or bedsores.";
    }
    if ($data['lung_diseases']) {
        $messages[] = "Lung disease disqualifies you from blood donation. Follow a diet rich in antioxidants, such as berries, spinach, and nuts, to support lung health. Avoid pollutants, cigarette smoke, and processed foods. Regular breathing exercises, light physical activity, and prescribed medications can help improve your lung function. Consult your pulmonologist regularly for updates on your condition.";
    }
    if ($data['liver_diseases']) {
        $messages[] = "With liver disease, you cannot donate blood. Eat a liver-friendly diet that includes leafy greens, berries, and lean proteins, while avoiding alcohol and high-fat or processed foods. Stay hydrated and exercise regularly to maintain a healthy weight. Follow your doctor’s advice regarding medications and regular check-ups to monitor liver function and prevent further damage.";
    }
    if ($data['kidney_diseases']) {
        $messages[] = "Kidney disease restricts you from blood donation. Focus on a low-sodium, low-potassium, and low-phosphorus diet with lean proteins and fresh vegetables. Drink plenty of water to support kidney function. Avoid processed foods and high-salt snacks. Regular exercise and stress management are key. Follow your nephrologist’s recommendations and attend regular check-ups to manage your condition.";
    }
    if ($data['blood_diseases']) {
        $messages[] = "Blood disorders prevent you from donating blood. A diet rich in iron (such as spinach, beans, and lean meats) and vitamin C (like citrus fruits) can help improve blood health. Avoid alcohol, smoking, and any unnecessary medications that could affect blood quality. Regular follow-ups with your hematologist are essential to monitor your blood health and manage your condition effectively.";
    }
    if ($data['cancer']) {
        $messages[] = "Having cancer disqualifies you from donating blood. Support your recovery with a nutrient-rich diet, including lean proteins, fresh fruits, and vegetables, to aid in healing and energy. Stay hydrated and rest as needed. Follow your oncologist’s treatment plan closely, including prescribed medications, and attend regular check-ups. Manage stress with light physical activities, such as walking or yoga, and ensure sufficient rest.";
    }

    if (!empty($messages)) {
        return implode(" ", $messages); // Combine all messages into one string
    }

    return "";
}
}
?>
