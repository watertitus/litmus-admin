<div class="col-md-12">

    <?php

    try {

        $email = $_GET['id'];
        $sql = "SELECT * FROM  user_profile WHERE 
                    -- user_profile.user_email=users.email AND nok.user_email=users.email  AND dbs.user_email=users.email AND 
                    user_email=:email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        if ($users = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            # code...

    ?>


            <div class="card">
                <div class="card-body ">
                    <h5 class="card-title text-capitalize">Applicants Bio-Data</h5>
                    <h6 class="card-subtitle mb-4 text-muted text-capitalize">Created <?php echo get_time_ago(strtotime($users['created_at']))  ?></h6>
                    <div class="row">
                        <div class="col-md-6">

                            <p class="card-text"><strong>Applicant's Name: </strong> <?php echo $users['fname']  ?> <?php echo $users['lname']  ?></p>
                            <p class="card-text"><strong>Applicant's Email: </strong> <?php echo $users['user_email']  ?></p>
                            <p class="card-text"><strong>Applicant's Mobile </strong><?php echo $users['mobile']  ?> </p>
                            <p class="card-text"><strong>Applicant's Birthday: </strong><?php echo $users['birth_day'] . '/' . $users['birth_month'] . '/' . $users['birth_year'] ?> </p>


                        </div>
                        <div class="col-md-6">
                            <p class="card-text"><strong>Applicant's Address:</strong><?php echo $users['address_line'] . ', ' . $users['address_line'] . ', ' . $users['state']  ?> </p>
                            <p class="card-text"><strong>Applicant's Post Code:</strong><?php echo $users['postcode']  ?> </p>
                            <p class="card-text"><strong>Applicant's DBS: </strong><?php echo $users['dbs']  ?> </p>
                            <p class="card-text"><strong>Applicant's Covide: </strong><?php echo $users['covid']  ?> </p>
                            <!-- <form>
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit" disabled>Submit form</button>
                                </div>
                            </form> -->
                        </div>

                    </div>

                </div>
                <div class="card-footer ">
                    <h6 class="text-muted text-capitalize">Updated <?php echo get_time_ago(strtotime($users['updated_at']))  ?></h6>

                </div>

        <?php
        }
    } catch (Exception $th) {
        echo $th->getMessage();
    }
        ?>
            </div>
</div>