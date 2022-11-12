<div class="col-md-12">
    <?php

    try {

        $email = $_GET['email'];
        $sql = "SELECT * FROM  qualification WHERE 
                    user_email=:email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($gest as $key => $users) {
                # code...
                if (isset($_POST['rejectQual'])) {
                    try {
                        $id = $_POST['id'];
                        $cand_email = $_POST['user_email'];
                        $table = 'qualification';
                        $query = "DELETE FROM " . $table . " WHERE " . $table . "_id=?";
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
                        $mail->Subject = 'Urgent: Qualification/Certification Declined';
                        if ($users['qualification_type'] === "others") {
                            $qualType = $users['qualification_type'];
                        } else {
                            $other_cert = $users['other_cert'];
                        }
                        /* Set the mail message body. */
                        $html = file_get_contents("https://litmusservices.co.uk/api/EmailTemplate.html");
                        $html = str_replace("{EmailTitle}", 'Qualification/Certification Declined: ' . $qualType . $other_cert . ' ' . $users['ref_fname'], $html);
                        $html = str_replace("{EmailContent1}", "Dear " . $cand_email, $html);
                        $html = str_replace("{EmailContent2}", "Your Qualification Doc has just been declined or Rejected by the Litmus team.<br/><br/>
                        Kindly logon to your dashboard to provide another Quaalification or Certification", $html);
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
                } elseif (isset($_POST['approveQual'])) {

                    try {
                        $id = $_POST['id'];
                        $cand_email = $_POST['user_email'];
                        $table = 'qualification';
                        $isRefApproved = 'true';
                        $query = "UPDATE " . $table . " 
                                    SET isRefApproved = :isRefApproved
                                WHERE   referee_id = :id";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':id', $id);
                        $stmt->bindParam(':isRefApproved', $isRefApproved);

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
                        $html = str_replace("{EmailTitle}", 'Reference Approved: ' . $users['ref_fname'] . ' ' . $users['ref_fname'], $html);
                        $html = str_replace("{EmailContent1}", "Congratulations", $html);
                        $html = str_replace("{EmailContent2}", "Your Qualifcation/Certifcation had just been Approved by the Litmus team.<br/><br/>
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
                        if ($users['status'] === 1) { ?>
                            <p class="h3 text-success"><i class="bi bi-check2-circle"></i></p>
                        <?php       } else { ?>
                            <form method="POST">
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <input name="id" value="<?php echo $users['qualification_id'] ?>" type="hidden">
                                    <input name="user_email" value="<?php echo $users['user_email'] ?>" type="hidden">
                                    <button name="approveQual" type="submit" class="btn btn-lg btn-success"> <i class="bi bi-person-check"></i></button>
                                    <button name="rejectQual" type="submit" class="btn  btn-lg btn-danger"><i class="bi bi-person-x"></i> </button>
                                </div>
                            </form>
                        <?php } ?>
                    </th>
                </tr>
                <tr>
                    <td>Name of Certifiction/Qualification:</td>
                    <td><?php if ($users['qualification_type'] === "others") {
                            echo $users['qualification_type'];
                        } else {
                            echo $users['other_cert'];
                        }  ?></td>
                </tr>
                <tr>
                    <td>File:</td>
                    <td><img src="<?php echo $users['qualification_file']  ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>

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