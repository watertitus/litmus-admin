<div class="col-md-12">
    <?php

    try {

        $email = $_GET['id'];
        $sql = "SELECT * FROM  dbs WHERE 
                    -- user_profile.user_email=users.email AND nok.user_email=users.email  AND dbs.user_email=users.email AND 
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
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">Applicants Bio-Data</h5>
                        <h6 class="card-subtitle mb-2 text-muted text-capitalize">Updated <?php echo get_time_ago(strtotime($users['updated_at']))  ?></h6>
                        <p class="card-text"><strong>Applicant's Name: </strong> <?php echo $users['nok_fname']  ?> <?php echo $users['nok_lname']  ?></p>
                        <p class="card-text"><strong>Applicant's Email: </strong> <?php echo $users['nok_email']  ?></p>
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
                    <h5 class="card-title text-capitalize">Applicant's DBS</h5>
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