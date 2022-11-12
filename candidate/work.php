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

    ?>
                <tr>
                    <th>Work Permit</th>
                    <th>
                        <?php
                        if ($users['status'] === 'true') { ?>
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
                    <td>Applicant's Visa Type:</td>
                    <td><?php echo $users['visa_type']  ?></td>
                </tr>
                <tr>
                    <td>Applicant's Insurance Number:</td>
                    <td><?php echo $users['visa_type']  ?></td>
                </tr>
                <tr>
                    <td>Applicant's Reasons for NIN above:</td>
                    <td><?php echo $users['visa_type']  ?></td>
                </tr>
                <tr>
                    <td>Applicant's Visa Type:</td>
                    <td><?php echo $users['visa_type']  ?></td>
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