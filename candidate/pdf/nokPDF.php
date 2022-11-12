<?php
session_start();
if (!$_SESSION['admin']) {
    header('Location: login');
}
include_once './../config/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require './../vendor/autoload.php';
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();


require('../vendor/fpdf/fpdf.php');


if (isset($_POST['download'])) {
    class PDF extends FPDF
    {
        // Page header
        function Header()
        {
            // Logo
            $this->Image('http://localhost:3000/litmusLogo.png', 10, 6, 30);
            // Arial bold 15
            $this->SetFont('Arial', 'B', 15);
            // Move to the right
            $this->Cell(80);
            // Title
            $this->Cell(30, 10, 'Litmus Services', 1, 0, 'C');
            // Line break
            $this->Ln(20);
        }

        // Page footer
        function Footer()
        {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial', 'I', 8);
            // Page number
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    $pdf = new FPDF();




    $email = $_POST['user_email'];
    $user_name = $_POST['user_name'];
    $sql = "SELECT * FROM  nok WHERE 
                    user_email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $count = 0;
    $pdf->AddPage();
    $pdf->AliasNbPages();
    $pdf->SetFont('arial', 'B', 16);
    $pdf->Cell(0, 15, "Next of Kin Details for " . $_POST['user_name'], 0, 1, "L");
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(0, 7, "Applicant Email: " . $email, 0, 1, "L");
    if ($stmt->rowCount()) {
        $gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($gest as $key => $users) {

            //line
            $pdf->SetFont('times', '', 12);
            $pdf->Cell(30, 7, "Next of Kin's name: ", 0, 0);
            $pdf->Cell(30, 7, $users['nok_fname'] . ' ' . $users['nok_fname'], 0, 1);
            //line
            $pdf->Cell(30, 7, "Next of Kin's Email: ", 0, 0);
            $pdf->Cell(60, 7, $users['nok_email'], 0, 1);
            //line
            $pdf->Cell(30, 7, "Next of Kin's Mobile: ", 0, 0);
            $pdf->Cell(30, 7, $users['nok_mobile'], 0, 1);
 //line
 $pdf->Cell(30, 7, "Next of Kin's Address: ", 0, 0);
 $pdf->Cell(30, 7, $users['nok_address'], 0, 1);
  //line
  $pdf->Cell(30, 7, "Next of Kin's Relation: ", 0, 0);
  $pdf->Cell(30, 7, $users['nok_relation'], 0, 1);
          
        }
    }
    $pdf->SetAutoPageBreak(1, 1);
    $pdf->Output();
}
