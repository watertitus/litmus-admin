<?php
// session_start();
// if (!$_SESSION['admin']) {
//     header('Location: login');
// }
include_once './config/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require './vendor/autoload.php';
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();


$sql = "SELECT * FROM users, user_profile WHERE users.email=user_profile.user_email ";
$stmt = $conn->prepare($sql);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    echo $stmt->rowCount();
} else {

    echo '<span class="text-danger">' . $stmt->rowCount() . '</span>';
}
