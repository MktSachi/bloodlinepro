<?php
require_once '../Classes/Database.php';
require_once '../Classes/BloodRequest.php';

$db = new Database();
$bloodRequest = new BloodRequest($db->getConnection());

if (isset($_POST['hospitalID'])) {
    $hospitalID = intval($_POST['hospitalID']);
    $bloodGroups = $bloodRequest->getAvailableBloodGroups($hospitalID);

    if (!empty($bloodGroups)) {
        foreach ($bloodGroups as $bloodGroup) {
            echo "<option value=\"$bloodGroup\">$bloodGroup</option>";
        }
    } else {
        echo "<option value=\"\">No blood groups available</option>";
    }
}
?>
