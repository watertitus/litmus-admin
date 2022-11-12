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

class PDF extends FPDF
{
function WordWrap(&$text, $maxwidth)
{
    $text = trim($text);
    if ($text==='')
        return 0;
    $space = $this->GetStringWidth(' ');
    $lines = explode("\n", $text);
    $text = '';
    $count = 0;

    foreach ($lines as $line)
    {
        $words = preg_split('/ +/', $line);
        $width = 0;

        foreach ($words as $word)
        {
            $wordwidth = $this->GetStringWidth($word);
            if ($wordwidth > $maxwidth)
            {
                // Word is too long, we cut it
                for($i=0; $i<strlen($word); $i++)
                {
                    $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                    if($width + $wordwidth <= $maxwidth)
                    {
                        $width += $wordwidth;
                        $text .= substr($word, $i, 1);
                    }
                    else
                    {
                        $width = $wordwidth;
                        $text = rtrim($text)."\n".substr($word, $i, 1);
                        $count++;
                    }
                }
            }
            elseif($width + $wordwidth <= $maxwidth)
            {
                $width += $wordwidth + $space;
                $text .= $word.' ';
            }
            else
            {
                $width = $wordwidth + $space;
                $text = rtrim($text)."\n".$word.' ';
                $count++;
            }
        }
        $text = rtrim($text)."\n";
        $count++;
    }
    $text = rtrim($text);
    return $count;
}
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
if (isset($_POST['download'])) {
   

    $pdf = new FPDF();




    $email = $_POST['user_email'];
    $user_name = $_POST['user_name'];
    $email = $_POST['user_email'];
    $email = $_POST['user_email'];
    $sql = "SELECT * FROM  referee WHERE 
                    user_email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $count = 0;
    $pdf->AddPage();
    $pdf->AliasNbPages();
    $pdf->SetFont('arial', 'B', 16);
    $pdf->Cell(0, 15, "Referee Details for " . $_POST['user_name'], 0, 1, "L");
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(0, 7, "Applicant Email: " . $email, 0, 1, "L");
    if ($stmt->rowCount()) {
        $gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($gest as $key => $users) {

            $pdf->SetFont('Times', 'B', 12);
            $pdf->Cell(0, 7, "Referee Details " . $count += 1, 1, 1, "L");
            //line
            $pdf->SetFont('times', '', 9);
            $pdf->Cell(30, 7, "Referee name: ", 0, 0);
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
                $pdf->Cell(50, 7, "Organisation: ", 0, 0);
                $pdf->Cell(50, 7, $users['organisation'], 0, 1);
                //line
                $pdf->Cell(50, 7, "Position ", 0, 0);
                $pdf->Cell(60, 7, $users['ref_candidate_position'], 0, 1);
                $pdf->Cell(50, 7, "Other position ", 0, 0);
                $pdf->Cell(50, 7, $users['ref_other_position'], 0, 1);
                //line
                $pdf->Cell(50, 7, "Communication:", 0, 0);
                $pdf->Cell(60, 7, $users['candidate_communication'], 0, 1);

                $pdf->Cell(50, 7, "Punctuality: ", 0, 0);
                $pdf->Cell(50, 7, $users['candidate_punctuality'], 0, 1);
                //line
                $pdf->Cell(50, 7, "Professionalism/conduct: ", 0, 0);
                $pdf->Cell(50, 7, $users['candidate_conduct'], 0, 1);
                //line
                $pdf->Cell(50, 7, "Reliability/timekeeping: ", 0, 0);
                $pdf->Cell(50, 7, $users['candidate_reliability'], 0, 1);
                //line
                $pdf->Cell(50, 7, "Job Suitability: ", 0, 0);
                $pdf->Cell(50, 7, $users['candidate_suitability'], 0, 1);
                //line
                $pdf->SetFont('times', 'B', 9);
                $text = $users['ref_repsonse'];
                $pdf->Cell(50, 7, "Additional, relevant comments: ", 0, 1);
                $pdf->SetFont('times', '', 9);
                $pdf->Write(5,$text);
                
                // $pdf->Cell(100, 0, $users['ref_repsonse'], 0, 1);
            } else {
                $pdf->Cell(50, 7, "Referee hasn't responded yet!", 0, 1);
            }
            $pdf->Ln();
        }
    }
    $pdf->SetAutoPageBreak(1, 1);
    $pdf->Output();
}
