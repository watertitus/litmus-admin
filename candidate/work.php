<div class="col-md-12">
    <?php

    try {

        $email = $_GET['email'];
        $sql = "SELECT * FROM  work_permit WHERE 
                    user_email=:email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($gest as $key => $users) {
                # code...
                if (isset($_POST['reject'])) {
                    try {
                        $id = $_POST['id'];
                        $cand_email = $_POST['user_email'];
                        $table = 'work_permit';
                        $query = "DELETE FROM " . $table . " WHERE id=?";
                        $stmt = $conn->prepare($query);

                        $mail->isSMTP();                            // Set mailer to use SMTP 
                        $mail->Host = 'litmusservices.co.uk';           // Specify main and backup SMTP servers 
                        $mail->SMTPAuth = true;                     // Enable SMTP authentication 
                        $mail->Username = 'team@litmusservices.co.uk';       // SMTP username 
                        $mail->Password = 'AwfU$;HYR2=E';         // SMTP password 
                        $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted 
                        $mail->Port = 465;                          // TCP port to connect to 
                        $mail->isHTML(true);

                        $mail->setFrom('team@litmusservices.co.uk', 'Litmus Services ');
                        /* Add a recipient. */
                        $mail->addAddress($cand_email);

                        /* Set the subject. */
                        $mail->Subject = 'Urgent: Reference Declined';

                        /* Set the mail message body. */
                        $date = date_create($users['expiry']);
                        $html = file_get_contents("https://litmusservices.co.uk/api/EmailTemplate.html");
                        $html = str_replace("{EmailTitle}", 'Work Permit Documnet Declined: ' . $users['visa_type'] . ' with Expiry date' . date_format($date, "l m F, Y"), $html);
                        $html = str_replace("{EmailContent1}", "Dear " . $cand_email, $html);
                        $html = str_replace("{EmailContent2}", $users['visa_type'] . ' with Expiry date' . date_format($date, "l m F, Y") . " has just been declined Rejected by the Litmus team.<br/><br/>
                        Kindly logon to your dashboard to provide another referee immediately", $html);
                        $html = str_replace(
                            "{EmailContent3}",
                            "Thank you.",
                            $html
                        );
                        $html = str_replace(
                            "{Farewel}",
                            "Best Regards<br/>Litmus Services Team.",
                            $html
                        );
                        $mail->Body = $html;

                        if ($stmt->execute([$id]) && $mail->send()) {
                            $succ_sms = "referee Rejected!";
                        }
                    } catch (Exception $th) {
                        echo $th->getMessage();
                    }
                } elseif (isset($_POST['approve'])) {

                    try {
                        $id = $_POST['id'];
                        $cand_email = $_POST['user_email'];
                        $table = 'work_permit';
                        $isApproved = 'true';
                        $query = "UPDATE work_permit 
                                    SET isApproved = :isApproved
                                WHERE   id = :id";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':id', $id);
                        $stmt->bindParam(':isApproved', $isApproved);

                        $mail->isSMTP();                            // Set mailer to use SMTP 
                        $mail->Host = 'litmusservices.co.uk';           // Specify main and backup SMTP servers 
                        $mail->SMTPAuth = true;                     // Enable SMTP authentication 
                        $mail->Username = 'team@litmusservices.co.uk';       // SMTP username 
                        $mail->Password = 'AwfU$;HYR2=E';         // SMTP password 
                        $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted 
                        $mail->Port = 465;                          // TCP port to connect to 

                        $mail->setFrom('team@litmusservices.co.uk', 'Litmus Services ');
                        /* Add a recipient. */
                        $mail->addAddress($cand_email);
                        $mail->isHTML(true);
                        /* Set the subject. */
                        $mail->Subject = 'Congratulations: One of your referee is approved';
                        $date = date_create($users['expiry']);
                        /* Set the mail message body. */
                        $html = file_get_contents("https://litmusservices.co.uk/api/EmailTemplate.html");
                        $html = str_replace("{EmailTitle}", 'Your ' . $users['visa_type'] . ' with Expiry date' . date_format($date, "l m F, Y"), $html);
                        $html = str_replace("{EmailContent1}", "Congratulations", $html);
                        $html = str_replace("{EmailContent2}", "Your Compliance Documnent" . $users['visa_type'] . ' with Expiry date' . date_format($date, "l m F, Y") . " has just been Approved by the Litmus team.<br/><br/>
                        If you are yet to complete your profile, kindly do so as soon as possible ", $html);
                        $html = str_replace(
                            "{EmailContent3}",
                            "Thank you.",
                            $html
                        );
                        $html = str_replace(
                            "{Farewel}",
                            "Best Regards<br/>Litmus Services Team.",
                            $html
                        );
                        $mail->Body = $html;
                        if ($stmt->execute() && $mail->send()) {
                            http_response_code(200);
                            // echo json_encode(http_response_code(200));
                        }
                    } catch (Exception $th) {
                        http_response_code(400);
                        echo $th->getMessage();
                    }
                }

    ?>
                <tr>
                    <th>Work Permit</th>
                    <th>
                        <?php
                        if ($users['isApproved'] === 'true') { ?>
                            <p class="h3 text-success"><i class="bi bi-check2-circle"></i></p>
                        <?php       } else { ?>
                            <form method="POST">
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <input name="id" value="<?php echo $users['id'] ?>" type="hidden">
                                    <input name="user_email" value="<?php echo $users['user_email'] ?>" type="hidden">
                                    <button name="approve" type="submit" class="btn btn-lg btn-success"> <i class="bi bi-person-check"></i></button>
                                    <button name="reject" type="submit" class="btn  btn-lg btn-danger"><i class="bi bi-person-x"></i> </button>
                                </div>
                            </form>
                        <?php } ?>
                    </th>
                </tr>
                <tr>
                    <td>Nationality:</td>
                    <td><?php echo $users['nationality']  ?></td>
                </tr>
                <tr>
                    <td>Visa Type:</td>
                    <td><?php echo $users['visa_type']  ?></td>
                </tr>
                <tr>
                    <td>Insurance Number:</td>
                    <td><?php echo $users['insurancenumber']  ?></td>
                </tr>
                <tr>
                    <td>File:</td>
                    <td><?php echo $users['permit_file']  ?></td>
                </tr>
                <tr>
                    <td>Expiry Date:</td>
                    <td><?php echo $users['expiry']  ?></td>
                </tr>


            <?php
            }
        } else { ?>
            <div class="card ">
                <div class="card-body">
                    <h5 class="card-title text-capitalize">Applicant's Work Permit</h5>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Data cannot be retrieved at the moment!</h4>
                        <p>It may seems that the applicant has not Completed their Work Permit form.</p>

                    </div>

                </div>
            </div>

    <?php  }
    } catch (Exception $th) {
        echo $th->getMessage();
    }
    ?>
</div>