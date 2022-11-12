<?php
session_start();
if (!$_SESSION['admin']) {
    header('Location: login');
}
include_once './config/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require './vendor/autoload.php';
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$admin_email = $_SESSION['admin'];
$sql = "SELECT * FROM admin WHERE admin_email=:admin_email";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':admin_email', $admin_email);
$stmt->execute();
 $row = $stmt->fetch(PDO::FETCH_ASSOC);
 echo $row['username'];
