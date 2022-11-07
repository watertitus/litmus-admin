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

"SELECT fname,lname, user_email, expmonth, expyear, DATEDIFF(expired_month, date('F') remaining_days";
$sql = "SELECT * FROM work_permit, user_profile WHERE work_permit.user_email=user_profile.user_email ";
$stmt = $conn->prepare($sql);
$stmt->execute();
$gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($gest as $key => $users) { ?>
    <tr>
        <td class="col-3">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-md">
                    <?php if (empty($users['user_avatar'])) { ?>
                        <img src="./assets/images/litmus_user_avatar.jpg">
                    <?php } else { ?>
                        <img src="<?php echo $users['user_avatar'] ?>">
                    <?php } ?>
                </div>
                <p class="font-bold ms-3 mb-0"><?php echo $users['fname'] . ' ' . $users['lname'] ?></p>
            </div>
        </td>
        <td class="col-auto">
            <p class=" mb-0">Congratulations on your graduation!</p>
        </td>
        <td class="col-auto">
            <p class=" mb-0"><a href="">Send Reminder</a></p>
        </td>
    </tr>
<?php }
