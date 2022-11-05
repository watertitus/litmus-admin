<div class="col-md-12">
    <?php

    try {

        $email = $_GET['id'];
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

                <div class="card">
                    <div class="card-body ">
                        <h5 class="card-title text-capitalize">Applicants Bio-Data</h5>
                        <h6 class="card-subtitle mb-4 text-muted text-capitalize">Created <?php echo get_time_ago(strtotime($users['created_at']))  ?></h6>
                        <div class="row">
                            <div class="col-md-5">
                                <p class="card-text"><strong>Applicant's Visa Type: </strong> <?php echo $users['visa_type']  ?> </p>
                                <p class="card-text"><strong>Applicant's Insurance Number: </strong> <?php echo $users['insurancenumber']  ?></p>
                                <p class="card-text"><strong>Applicant's Reasons for NIN above </strong><?php echo $users['reason']  ?> </p>
                                <p class="card-text"><strong>Applicant's Address:</strong><?php echo $users['value'] ?> </p>

                            </div>
                            <div class="col-md-5">
                                <p class="card-text"><strong>Applicant's Utility bill or Bank statement:</strong>
                                    <iframe src="<?php echo $users['bill'] ?>"></iframe>
                                </p>
                                <p class="card-text"><strong>Applicant's Driver's licence or Tenancy agreement </strong><?php echo $users['nationality']  ?> </p>
                                <p class="card-text"><strong>Applicant's Nationality </strong><?php echo $users['nationality']  ?> </p>
                                <p class="card-text"><strong>Applicant's Data page of International passport: </strong><?php echo $users['expmonth'] . '/' . $users['expyear']  ?> </p>
                                <p class="card-text"><strong>Applicant's Covide: </strong><?php echo $users['licence']  ?> </p>
                            </div>
                        </div>
                        <form>
                            <input type="button" value="Approve">
                        </form>
                    </div>
                </div>
            <?php
            }
        } else { ?>
            <div class="card ">
                <div class="card-body">
                    <h5 class="card-title text-capitalize">Applicant's Work Permit</h5>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Data cannot be retrieved at the moment!</h4>
                        <p>It may seems that the applicant has not Completed their Next of Kin form.</p>

                    </div>

                </div>
            </div>

    <?php  }
    } catch (Exception $th) {
        echo $th->getMessage();
    }
    ?>
</div>