<?php
session_start();
include_once 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once('PHPMailer-master/src/PHPMailer.php');
require_once('PHPMailer-master/src/Exception.php');
require_once('PHPMailer-master/src/SMTP.php');

    $obj = new DBH;
    $con = $obj->connect();
    
    

if(isset($_POST['email_in']) && $_POST['address'])
{
    $address = $_POST['address'];
    $sql = "SELECT * FROM Owners WHERE email = '$address'";
    $res = $con->prepare($sql);
    $res->execute();
    $owner = $res->fetch();
        $token = bin2hex(random_bytes(25));
        $email = $owner['email'];
        $user = $owner['username'];
        $sql = "UPDATE Owners SET token='$token' where email='$email'";
        $res = $con->prepare($sql);
        $res->execute();
        $mailer = new PHPMailer(true);
        $mailer->CharSet = "utf-8";
        $mailer->IsSMTP();
        $mailer->SMTPAuth = true;
        $mailer->Username = "finaltestlogue@gmail.com";
        $mailer->Password = "mqkjlphzkmtckrsw";
        $mailer->SMTPSecure = "tls";
        $mailer->Host = "smtp.gmail.com";
        $mailer->Port = "587";
        $mailer->From = "placeholder@gmail.com";
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->addAddress($email, $user);
        $mailer->IsHTML(true);
        $mailer->Subject = 'Reset Password';
        $mailer->Body = "Click this <a href=http://localhost/final/reset.php?token=" .$token."> link</a> to reset your password. ";
        if($mailer->Send())
        {
            echo "<script>alert('Check your email and click the link to reset your password.');</script>";
        }
        else
        {
            echo "Error.";
        }

}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="./css/resetcont.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <header>
            <div id="main-bar">
                <img id="logo" src="./images/logo2.png"></img>
                <button onclick="document.location='index.php'" type="button" id="idBtnHome"
                    class="btn btn-link">Home</button>
                <button type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
            </div>
        </header>
        <div class="container">
            <form method="POST" action="" class="form">
                <p> Enter email address. A link will be sent to reset the password of the assosiated account. </p>
                <input name="address" type="text" placeholder="address@email.com">
                <input name="email_in" type="submit">
            </form>
        </div>
    </body>
</html>
