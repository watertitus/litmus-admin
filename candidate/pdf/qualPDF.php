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
        function WordWrap(&$text, $maxwidth)
        {
            $text = trim($text);
            if ($text === '')
                return 0;
            $space = $this->GetStringWidth(' ');
            $lines = explode("\n", $text);
            $text = '';
            $count = 0;

            foreach ($lines as $line) {
                $words = preg_split('/ +/', $line);
                $width = 0;

                foreach ($words as $word) {
                    $wordwidth = $this->GetStringWidth($word);
                    if ($wordwidth > $maxwidth) {
                        // Word is too long, we cut it
                        for ($i = 0; $i < strlen($word); $i++) {
                            $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                            if ($width + $wordwidth <= $maxwidth) {
                                $width += $wordwidth;
                                $text .= substr($word, $i, 1);
                            } else {
                                $width = $wordwidth;
                                $text = rtrim($text) . "\n" . substr($word, $i, 1);
                                $count++;
                            }
                        }
                    } elseif ($width + $wordwidth <= $maxwidth) {
                        $width += $wordwidth + $space;
                        $text .= $word . ' ';
                    } else {
                        $width = $wordwidth + $space;
                        $text = rtrim($text) . "\n" . $word . ' ';
                        $count++;
                    }
                }
                $text = rtrim($text) . "\n";
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

    $pdf = new FPDF();




    $email = $_POST['user_email'];
    $user_name = $_POST['user_name'];
    $email = $_POST['user_email'];
    $sql = "SELECT * FROM  qualification WHERE 
                    user_email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $count = 0;
    $pdf->AddPage();
    $pdf->AliasNbPages();
    $pdf->SetFont('arial', 'B', 16);
    $pdf->Cell(0, 15, "Qualification Document for " . $_POST['user_name'], 0, 1, "L");
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(0, 7, "Applicant Email: " . $email, 0, 1, "L");
    if ($stmt->rowCount()) {
        $gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($gest as $key => $users) {

            $pdf->SetFont('times', '', 9);
            $pdf->Cell(30, 7, "Qualification  Type: ", 0, 0);
            if ($users['qualification_type'] != 'others') {
                //line
                $pdf->Cell(30, 7, $users['qualification_type'], 0, 1);
            } else {
                $pdf->Ln();
                $pdf->SetFont('times', '', 9);
                $text = $users['other_cert'];
                $pdf->Write(5, $text);
                // $pdf->Cell(30, 7, $users['other_cert'], 0, 1);
                $pdf->Ln();
            }

            //line
            $pdf->Cell(30, 7, "Expiry Date: ", 0, 0);

            $pdf->Cell(60, 7, $users['qual_expiry'], 0, 1);
            //line
            $pdf->Cell(30, 7, "File: ", 0, 0);
            $pdf->Cell(30, 7, $users['qualification_file'], 0, 1);
            $url = strval($users['qualification_file']);
            echo $ext = pathinfo($url, PATHINFO_EXTENSION);

            $file_info = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $file_info->buffer(file_get_contents($users['qualification_file']));
            if ($mime_type === 'image/png') {
                $mime_type = "PNG";
                $pdf->Image($url, 20, 90, 0, 0, $mime_type);
                $pdf->Ln();
            }elseif($mime_type === 'image/jpeg'){
                $mime_type = "JPG";
                $pdf->Image($url, 20, 90, 0, 0, $mime_type);
                $pdf->Ln();
            }elseif($mime_type === 'image/jpg'){
                $mime_type = "JPG";
                $pdf->Image($url, 20, 90, 0, 0, $mime_type);
                $pdf->Ln();
            }elseif($mime_type === 'image/gif'){
                $mime_type = "GIF";
                $pdf->Image($url, 20, 90, 0, 0, $mime_type);
                $pdf->Ln();
            }

            if ($users['isApproved'] === 'true') {
                //line
                $pdf->SetFont('times', 'B', 12);
                $pdf->SetFillColor(100,100,2);
                $pdf->SetTextColor(0, 148, 25);
                $pdf->Cell(30, 7, "Compliance Document is Approved ", 0, 1);
                $pdf->SetTextColor(0, 0, 0);
                //line

            } else {
                $pdf->SetFont('times', 'B', 12);
                $pdf->SetTextColor(222, 9, 9);
                $pdf->Cell(50, 7, "Compliance Document hass not been Approved!", 0, 1);
                $pdf->SetTextColor(0, 0, 0);

            }
            $pdf->AddPage();
        }
    }
    // $pdf->SetAutoPageBreak(1, 1);

    $pdf->Output();
}
