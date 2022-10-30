<?php
    include "validate_admin.php";
    include "connect.php";
    include "header.php";
    include "user_navbar.php";
    include "admin_sidebar.php";
    include "session_timeout.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/action_style.css">
</head>

<?php
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$gender = $_POST["gender"];
$dob = $_POST["dob"];
$email = $_POST["email"];
$phno = $_POST["phno"];
$address = $_POST["address"];
$o_balance = $_POST["o_balance"];
$cus_uname = $_POST["cus_uname"];
$cus_pwd = $_POST["cus_pwd"];

$sql0 = "SELECT MAX(cust_id) FROM customer";
$result = $conn->query($sql0);
$row = $result->fetch_assoc();
$id = $row["MAX(cust_id)"] + 1;

/*  Prevent mismatch between cust_id and benef/passbook table number.
    This is because when a row is deleted from customer AUTO_INCREMENT does
    not reset but keeps increasing.
    Hence resest AUTO_INCREMENT to $id and eleminate the error. */
$sql5 = "ALTER TABLE customer AUTO_INCREMENT=".$id;
$conn->query($sql5);

$sql1 = "CREATE TABLE passbook".$id."(
            trans_id INT NOT NULL AUTO_INCREMENT,
            trans_date DATETIME,
            remarks VARCHAR(255),
            debit INT,
            credit INT,
            balance INT,
            PRIMARY KEY(trans_id)
        )";

$sql2 = "CREATE TABLE beneficiary".$id."(
            benef_id INT NOT NULL AUTO_INCREMENT,
            benef_cust_id INT UNIQUE,
            email VARCHAR(30) UNIQUE,
            phone_no VARCHAR(20) UNIQUE,
            PRIMARY KEY(benef_id)
        )";

$sql3 = "INSERT INTO customer VALUES(
            NULL,
            '$fname',
            '$lname',
            '$gender',
            '$dob',
            '$email',
            '$phno',
            '$address',
            '$cus_uname',
            '$cus_pwd'
        )";

$sql4 = "INSERT INTO passbook".$id." VALUES(
            NULL,
            NOW(),
            'Opening Balance',
            '0',
            '$o_balance',
            '$o_balance'
        )";

?>

<body>
    <div class="flex-container">
        <div class="flex-item">
            <?php
            if (($conn->query($sql3) === TRUE)) { ?>
                <p id="info"><?php echo "Customer created successfully !\n"; ?></p>
        </div>

        <div class="flex-item">
            <?php
            if (($conn->query($sql1) === TRUE)) { ?>
                <p id="info"><?php echo "Passbook created successfully !\n"; ?></p>
            <?php
            } else { ?>
                <p id="info"><?php
                echo "Error: " . $sql1 . "<br>" . $conn->error . "<br>"; ?></p>
            <?php } ?>
        </div>

        <div class="flex-item">
            <?php
            if (($conn->query($sql4) === TRUE)) { ?>
                <p id="info"><?php echo "Passbook updated successfully !\n"; ?></p>
            <?php
            } else { ?>
                <p id="info"><?php
                echo "Error: " . $sql4 . "<br>" . $conn->error . "<br>"; ?></p>
            <?php } ?>
        </div>

        <div class="flex-item">
            <?php
            if (($conn->query($sql2) === TRUE)) { ?>
                <p id="info"><?php echo "Beneficiary created successfully !\n"; ?></p>
            <?php
            } else { ?>
                <p id="info"><?php
                echo "Error: " . $sql2 . "<br>" . $conn->error . "<br>"; ?></p>
            <?php } ?>
        </div>

            <?php
            } else { ?>
        </div>
        <div class="flex-item">
                <p id="info"><?php
                echo "Error: " . $sql3 . "<br>" . $conn->error . "<br>"; ?></p>
            <?php } ?>
        </div>
        <?php $conn->close(); ?>

        <div class="flex-item">
            <a href="./customer_add.php" class="button">Add Again</a>
        </div>

    </div>

</body>
</html>
