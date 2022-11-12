<?php
session_start();
if (!$_SESSION['admin']) {
    header('Location: login');
}
include_once '../../config/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require '../../vendor/autoload.php';
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();


require('../../vendor/fpdf/fpdf.php');


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
    $email = $_POST['user_email'];
    $email = $_POST['user_email'];
    $sql = "SELECT * FROM  work_permit WHERE 
                    user_email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    // $count = 0;
    $pdf->AddPage();
    $pdf->AliasNbPages();
    $pdf->SetFont('arial', 'B', 16);
    $pdf->Cell(0, 15, "Work Document for " . $_POST['user_name'], 0, 1, "L");
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(0, 7, "Applicant Email: " . $email, 0, 1, "L");
    if ($stmt->rowCount()) {
        $gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($gest as $key => $users) {

            $pdf->SetFont('times', '', 9);
            $pdf->Cell(30, 7, "Document  Type: ", 0, 0);
            $pdf->Cell(30, 7, $users['ref_fname'] . ' ' . $users['ref_fname'], 0, 1);
            //line
            $pdf->Cell(30, 7, "Referee Email: ", 0, 0);
            $pdf->Cell(60, 7, $users['ref_email'], 0, 0);
            //line
            $pdf->Cell(30, 7, "Referee Mobile: ", 0, 0);
            $pdf->Cell(30, 7, $users['ref_mobile'], 0, 1);

            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(0, 7, "Referee Accessment", 1, 1, "L");
            $pdf->SetFont('times', '', 9);
            if ($users['isRefResponded'] === 'true') {
                //line
                $pdf->Cell(30, 7, "organisation: ", 0, 0);
                $pdf->Cell(30, 7, $users['organisation'], 0, 1);
                //line
                $pdf->Cell(30, 7, "Applicant’s position ", 0, 0);
                $pdf->Cell(60, 7, $users['ref_candidate_position'], 0, 0);
                $pdf->Cell(30, 7, "Other Applicant’s position ", 0, 0);
                $pdf->Cell(30, 7, $users['ref_other_position'], 0, 1);
                //line
                $pdf->Cell(30, 7, "Candidate’s  communication:", 0, 0);
                $pdf->Cell(60, 7, $users['candidate_communication'], 0, 0);

                $pdf->Cell(50, 7, "candidate’s Punctuality: ", 0, 0);
                $pdf->Cell(50, 7, $users['candidate_punctuality'], 0, 1);
                //line
                $pdf->Cell(50, 7, "Candidate’s Professionalism/conduct: ", 0, 0);
                $pdf->Cell(50, 7, $users['candidate_conduct'], 0, 1);
                //line
                $pdf->Cell(50, 7, "Candidate’s Reliability/timekeeping: ", 0, 0);
                $pdf->Cell(50, 7, $users['candidate_reliability'], 0, 1);
                //line
                $pdf->Cell(50, 7, "Candidate’s Job Suitability: ", 0, 0);
                $pdf->Cell(50, 7, $users['candidate_suitability'], 0, 1);
                //line
                $pdf->Cell(50, 7, "Additional, relevant comments: ", 0, 0);
                $pdf->Cell(50, 7, $users['ref_repsonse'], 0, 1);
            } else {
                $pdf->Cell(50, 7, "Applicant's Referee hasn't responded yet!", 0, 1);
            }
            $pdf->Ln();
        }
    }
    $pdf->SetAutoPageBreak(1, 1);
    $pdf->Output();
}
