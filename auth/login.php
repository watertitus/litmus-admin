<?php
session_start();
// if (!$_SESSION['admin']) {

//    // header('Location: login');
// }
include_once '../config/database.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

if (isset($_POST['login'])) {
    $email = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM `admin` WHERE admin_email = :email";
    $stmt = $conn->prepare($query);
    //Bind value.
    $stmt->bindValue(':email', $email);
    //Execute.
    echo $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() > 0) {
        $password2 = $row['password'];
        $id = $row['id'];

        if (password_verify($password, $password2)) {
            function generateRandomString($length = 10)
            {
                return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
            }
            $secretKey  = generateRandomString();
            $_SESSION['admin'] = $email;
            $_SESSION['token'] = $$row['password'];
            
            echo '<script>window.location.replace("dashboard");</script>';
            exit;
        } else {
            echo '<script>alert("Login Failed")</script>';
        }
    } else {
        http_response_code(401);
        echo '<script>alert("Account does not exist")</script>';
    }
}