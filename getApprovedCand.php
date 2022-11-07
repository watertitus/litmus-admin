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

$status = 1;
$sql = "SELECT * FROM users WHERE users.status=:status";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':status', $status);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    echo $stmt->rowCount();
} else {

    echo '<span class="text-danger">' . $stmt->rowCount() . '</span>';
}
