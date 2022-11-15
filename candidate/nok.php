<?php

try {

    $email = $_GET['email'];
    $sql = "SELECT * FROM  nok WHERE 

                    user_email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    if ($stmt->rowCount()) {
        $gest = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($gest as $key => $users) {
            if (!empty($users['nok_email']) || !empty($users['nok_fname']) || !empty($users['nok_relation'])) { ?>
              
                <tr>
                    <td>Next of kin's Name: </td>
                    <td> <?php echo $users['nok_fname']  ?> <?php echo $users['nok_lname']  ?></td>
                    <td>Next of kin's Email: </td>
                    <td><?php echo $users['nok_email']  ?></td>
                </tr>
                <tr>
                    <td>Next of kin's Mobile: </td>
                    <td> <?php echo $users['nok_fname']  ?></td>
                    <td>Next of kin's Relationship: </td>
                    <td><?php echo $users['nok_relation']  ?></td>
                </tr>
                <tr>
                    <td>Next of kin's Address: </td>
                    <td> <?php echo $users['nok_address']  ?></td>
                    <td>Next of kin's Post Code: </td>
                    <td><?php echo $users['nok_postcode']  ?></td>
                </tr>
            <?php  } else { ?>
                <tr>
                    <td colspan="4"><strong><span class="text-danger">Next of Kin Information is Complete!</span></strong></td>
                </tr>
            <?php  }
            ?>
        <?php
        }
    } else { ?>
        <tr>
            <td colspan="4"><strong><span class="text-danger">Data cannot be retrieved at the moment!<br />
                        It may seems that the applicant has not Completed their Next of Kin form.</span></strong></td>
        </tr>

<?php  }
} catch (Exception $th) {
    echo $th->getMessage();
}
?>