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
$mail = new PHPMailer(TRUE);

function get_time_ago($time)
{
    $time_difference = time() - $time;

    if ($time_difference < 1) {
        return 'less than 1 second ago';
    }
    $condition = array(
        12 * 30 * 24 * 60 * 60 =>  'year',
        30 * 24 * 60 * 60       =>  'month',
        24 * 60 * 60            =>  'day',
        60 * 60                 =>  'hour',
        60                      =>  'minute',
        1                       =>  'second'
    );

    foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;

        if ($d >= 1) {
            $t = round($d);
            return 'about ' . $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Datatable Jquery - Mazer Admin Dashboard</title>

    <link rel="stylesheet" href="../assets/css/main/app.css">
    <link rel="stylesheet" href="../assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/png">

    <link rel="stylesheet" href="../assets/css/pages/fontawesome.css">
    <link rel="stylesheet" href="../assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../assets/css/pages/datatables.css">

</head>

<body>
    <div id="app">
        <?php include '../includes/sidebar.php' ?>
        <?php if (isset($_GET['email'])) { ?>
            <div id="main">
                <header class="mb-3">
                    <a href="#" class="burger-btn d-block d-xl-none">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                </header>

                <div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <?php
                                $email = $_GET['email'];
                                $sql = "SELECT * FROM  user_profile WHERE 
                    user_email=:email";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindValue(':email', $email);
                                $stmt->execute();

                                while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <h3><?php echo $user['fname'] . ' ' . $user['fname']; ?>'s Data</h3>
                                    <p class="text-subtitle text-muted">Registered <?php echo get_time_ago(strtotime($user['created_at'])) ?></p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">List</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Tables start -->
                    <section class="section">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class=" card-title text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Applicant's Referee</h5>
                                    </div>
                                    <div class="collapse" id="collapseExample">
                                        <div class="card-body">
                                            <table>
                                                <?php include './referee.php' ?>
                                                <tfoot>
                                                    <tr>
                                                        <th>
                                                            <form action="referencePDF" method="POST">
                                                                <input type="hidden" name="user_email" value="<?php echo $user['user_email'] ?>">
                                                                <input type="hidden" name="user_name" value="<?php echo $user['fname'] . ' ' . $user['fname']; ?>">

                                                                <input type="submit" name="download" class="btn btn-primary" value="Download PDF" />
                                                            </form>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="btn  btn-primary card-title text-capitalize" data-bs-target="#collapsenok" type="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseExample">Applicant's Next of Kin</h5>
                                    </div>
                                    <div class="collapse" id="collapsenok">
                                        <div class="card-body">
                                            <table class="table" id="table1">
                                                <tbody>
                                                    <?php include './nok.php' ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </section>
                    <!-- Basic Tables end -->
                </div>

                <footer>
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2021 &copy; Litmus Services</p>
                        </div>
                        <div class="float-end">
                            <p>Powered by <a href="https://waterwaysdigital.com/">Waterways</a></p>
                        </div>
                    </div>
                </footer>
            </div>
    <?php  }
                            }    ?>
    </div>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/app.js"></script>

    <script src="../assets/extensions/jquery/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
    <script src="../assets/js/pages/datatables.js"></script>

</body>

</html>