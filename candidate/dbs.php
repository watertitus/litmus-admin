<div class="col-md-12">
    <?php

    try {

        $email = $_GET['email'];
        $sql = "SELECT * FROM  dbs WHERE 
                    -- user_profile.user_email=users.email AND nok.user_email=users.email  AND dbs.user_email=users.email AND 
                    user_email=:email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($gest as $key => $users) {
                if (isset($_POST['rejectDBS'])) {
                    try {
                        $id = $_POST['id'];
                        $cand_email = $_POST['user_email'];
                        $table = 'dbs';
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

                        $mail->setFrom('jobs@litmusservices.co.uk', 'Litmus Services ');
                        /* Add a recipient. */
                        $mail->addAddress($cand_email);

                        /* Set the subject. */
                        $mail->Subject = 'Urgent: DBS Document Declined';
                        /* Set the mail message body. */
                        $html = file_get_contents("https://litmusservices.co.uk/api/EmailTemplate.html");
                        $html = str_replace("{EmailTitle}", 'DBS Document Declined', $html);
                        $html = str_replace("{EmailContent1}", "Dear " . $cand_email, $html);
                        $html = str_replace("{EmailContent2}", "Your DBS Doc has just been declined or Rejected by the Litmus team.<br/><br/>
                        Kindly logon to your dashboard to update your DBS Information ", $html);
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
                } elseif (isset($_POST['approveDBS'])) {

                    try {
                        $id = $_POST['id'];
                        $cand_email = $_POST['user_email'];
                        $table = 'dbs';
                        $isRefApproved = 'true';
                        $query = "UPDATE " . $table . " 
                                    SET isApproved = :isApproved
                                WHERE   id = :id";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':id', $id);
                        $stmt->bindParam(':isApproved', $isRefApproved);

                        $mail->isSMTP();                            // Set mailer to use SMTP 
                        $mail->Host = 'litmusservices.co.uk';           // Specify main and backup SMTP servers 
                        $mail->SMTPAuth = true;                     // Enable SMTP authentication 
                        $mail->Username = 'team@litmusservices.co.uk';       // SMTP username 
                        $mail->Password = 'AwfU$;HYR2=E';         // SMTP password 
                        $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted 
                        $mail->Port = 465;                          // TCP port to connect to 
                        $mail->isHTML(true);

                        $mail->setFrom('jobs@litmusservices.co.uk', 'Litmus Services ');
                        /* Add a recipient. */
                        $mail->addAddress($cand_email);

                        /* Set the subject. */
                        $mail->Subject = 'Congratulations: Your Qualifcation/Certifcation has been approved';

                        /* Set the mail message body. */
                        $html = file_get_contents("https://litmusservices.co.uk/api/EmailTemplate.html");
                        $html = str_replace("{EmailTitle}", 'Approval Notice for DBS ', $html);
                        $html = str_replace("{EmailContent1}", "Congratulations", $html);
                        $html = str_replace("{EmailContent2}", "Your DBS Document had just been Approved by the Litmus team.<br/><br/>
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
                            echo json_encode(http_response_code(200));
                        }
                    } catch (Exception $th) {
                        http_response_code(400);
                        echo $th->getMessage();
                    }
                }

    ?>
                <tr>
                    <th>Qualification</th>
                    <th>
                        <?php
                        if ($users['isApproved'] === 'true') { ?>
                            <p class="h3 text-success"><i class="bi bi-check2-circle"></i></p>
                        <?php       } else { ?>
                            <form method="POST">
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <input name="id" value="<?php echo $users['id'] ?>" type="hidden">
                                    <input name="user_email" value="<?php echo $users['user_email'] ?>" type="hidden">
                                    <button name="approveDBS" type="submit" class="btn btn-lg btn-success"> <i class="bi bi-person-check"></i></button>
                                    <!-- <button name="rejectDBS" type="submit" class="btn  btn-lg btn-danger"><i class="bi bi-person-x"></i> </button> -->
                                </div>
                            </form>
                        <?php } ?>
                    </th>
                </tr>
                <tr>
                    <td>File: </td>
                    <td><img src="<?php echo $users['dbsfile']  ?>" /></td>

                </tr>
                <tr>
                    <td>Expiry Date: </td>
                    <td><?php
                        $date = date_create($users['exp_date']);
                        echo date_format($date, "l m F, Y")   ?></td>

                </tr>

            <?php
            }
        } else { ?>
            <div class="card ">
                <div class="card-body">
                    <h5 class="card-title text-capitalize">Qualification</h5>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Data cannot be retrieved at the moment!</h4>
                        <p>It may seems that the applicant has not Completed their Qualification(s).</p>

                    </div>

                </div>
            </div>

    <?php  }
    } catch (Exception $th) {
        echo $th->getMessage();
    }
    ?>
</div>