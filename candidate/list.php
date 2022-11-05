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
                            <h3>All Candidate</h3>
                            <!-- <p class="text-subtitle text-muted">Powerful interactive tables with datatables (jQuery required)</p> -->
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
                    <div class="card">
                        <!-- <div class="card-header">
                            All Candidate
                        </div> -->
                        <div class="card-body">
                            <table class="table" id="table1">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    try {

                                        $sn = 0;
                                        $sql = "SELECT * FROM user_profile";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        while ($users = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            # code...

                                    ?>
                                            <tr>
                                            <td><?php echo $sn += 1  ?></td>
                                            <td> <?php echo $users['fname']  ?> <?php echo $users['lname']  ?></td>
                                                <td><?php echo $users['user_email']  ?></td>
                                                <td><?php echo $users['mobile']  ?></td>
                                                
                                                <td>
                                                    <span class="badge bg-success">Active</span>
                                                </td>
                                                <td>
                                                    <a href="view?email=<?php echo $users['user_email']; ?>" class="btn btn-sm btn-info">View</a>
                                                </td>
                                            </tr>

                                    <?php
                                        }
                                    } catch (Exception $th) {
                                        echo $th->getMessage();
                                    }
                                    ?>

                                </tbody>
                            </table>
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
    </div>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/app.js"></script>

    <script src="../assets/extensions/jquery/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
    <script src="../assets/js/pages/datatables.js"></script>

</body>

</html>