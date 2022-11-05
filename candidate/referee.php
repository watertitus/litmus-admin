<?php


try {

    $email = $_GET['email'];
    $sql = "SELECT * FROM  referee WHERE 
                    user_email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $count = 0;
    if ($stmt->rowCount()) {
        $gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($gest as $key => $users) {
            # code...
            if (isset($_POST['reject'])) {
                try {
                    $id = $_POST['id'];
                    $cand_email = $_POST['user_email'];
                    $table = 'referee';
                    $query = "DELETE FROM " . $table . " WHERE " . $table . "_id=?";
                    $stmt = $conn->prepare($query);

                    $mail->setFrom('jobs@litmusservices.co.uk', 'Litmus Services ');
                    /* Add a recipient. */
                    $mail->addAddress($cand_email);

                    /* Set the subject. */
                    $mail->Subject = 'Urgent: Reference Declined';

                    /* Set the mail message body. */
                    $html = file_get_contents("https://litmusservices.co.uk/api/EmailTemplate.html");
                    $html = str_replace("{EmailTitle}", 'Reference Declined: ' . $users['ref_fname'] . ' ' . $users['ref_fname'], $html);
                    $html = str_replace("{EmailContent1}", "Dear " . $cand_email, $html);
                    $html = str_replace("{EmailContent2}", $users['ref_fname'] . ' ' . $users['ref_fname'] . " has just been declined Rejected by the Litmus team.<br/><br/>
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
                    $table = 'referee';
                    $isRefApproved = 'true';
                    $query = "UPDATE referee 
                                SET isRefApproved = :isRefApproved
                            WHERE   referee_id = :id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':isRefApproved', $isRefApproved);
                   

                    $mail->setFrom('jobs@litmusservices.co.uk', 'Litmus Services ');
                    /* Add a recipient. */
                    $mail->addAddress($cand_email);

                    /* Set the subject. */
                    $mail->Subject = 'Congratulations: One of your referee is approved';

                    /* Set the mail message body. */
                    $html = file_get_contents("https://litmusservices.co.uk/api/EmailTemplate.html");
                    $html = str_replace("{EmailTitle}", 'Reference Approved: ' . $users['ref_fname'] . ' ' . $users['ref_fname'], $html);
                    $html = str_replace("{EmailContent1}", "Congratulations", $html);
                    $html = str_replace("{EmailContent2}", "Your Referee: " . $users['ref_fname'] . ' ' . $users['ref_fname'] . " has just been Approved by the Litmus team.<br/><br/>
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

                <th>
                    <strong> Refree <?php echo $count += 1; ?></strong>
                </th>
                <th>
                    <?php
                    if ($users['isRefApproved'] === 'true') { ?>
                        <p class="h3 text-success"><i class="bi bi-check2-circle"></i></p>
                    <?php       } else { ?>
                        <form method="POST">
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <input name="id" value="<?php echo $users['referee_id'] ?>" type="hidden">
                                <input name="user_email" value="<?php echo $users['user_email'] ?>" type="hidden">
                                <button name="approve" type="submit" class="btn btn-lg btn-success"> <i class="bi bi-person-check"></i></button>
                                <button name="reject" type="submit" class="btn  btn-lg btn-danger"><i class="bi bi-person-x"></i> </button>
                            </div>
                        </form>
                    <?php } ?>


                </th>

            </tr>
            <tr>
                <td>Referee name: </td>
                <td> <?php echo $users['ref_fname'] . ' ' . $users['ref_fname']   ?> </td>
            <tr>
            <tr>
                <td>Referee Email: </td>
                <td><?php echo $users['ref_email']  ?></td>
            </tr>
            <tr>
                <td>Referee Mobile: </td>
                <td> <?php echo $users['ref_mobile']  ?></td>
            </tr>
            <tr>
                <td colspan="4"><strong>Referee Response</strong> <small class="text-muted">Updated <?php echo get_time_ago(strtotime($users['updated_at']))  ?></small></td>
            </tr>
            <?php
            if ($users['isRefResponded'] === 'true') { ?>
                <tr>
                    <td>Year(s) of Relationship<br /> with the Referee: </td>
                    <td> <?php echo $users['ref_relationship_year']  ?></td>
                </tr>
                <tr>
                    <td>Position/Relationship with the Referee: </td>
                    <td><?php echo $users['ref_candidate_position']  ?></td>
                </tr>
                <tr>
                    <td>Other Position/Relationship with the Referee: </td>
                    <td> <?php echo $users['ref_other_position']  ?></td>
                </tr>
                <tr>
                    <td>Referee Response: </td>
                    <td><?php echo $users['ref_repsonse']  ?></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="4"><strong><span class="text-danger">Applicant's Referee hasn't responded yet!</span></strong></td>
                </tr>
            <?php }
            ?>

        <?php
        }
    } else { ?>
        <tr>
            <td colspan="4"><strong><span class="text-danger">Data cannot be retrieved at the moment!<br />
                        It may seems that the applicant has not Completed their Next of Reference form.</span></strong></td>
        </tr>




    <?php  }
} catch (Exception $th) { ?>
    <tr>
        <td colspan="4"><strong><span class="text-danger"> <?php echo $th->getMessage() ?></span></strong></td>
    </tr>

<?php } ?>