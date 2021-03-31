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
        echo "$email";
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
            echo "Check your email and click the link to reset your password.";
        }
        else
        {
            echo "Error.";
        }

}
?>

<!DOCTYPE html>
<html>
    <body>
        <form method="POST" action="">
            <p> Enter email address. A link will be sent to reset the password of the assosiated account. </p>
            <input name="address" type="text">
            <input name="email_in" type="submit">
        </form>
    </body>
</html>