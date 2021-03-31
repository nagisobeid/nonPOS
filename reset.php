<?php
    session_start();
    include_once 'db.php';

    $obj = new DBH;
	$con = $obj->connect();

    if(isset($_POST['reset']))
    {
        $password = $_POST['new_pass'];
        $password2 = $_POST['new_pass2'];
        $token = (isset($_GET['token']) ? $_GET['token'] : null);
        echo "$token borked";
        if($password == $password2){
            $sql = "SELECT email FROM Owners WHERE token='$token'";
            $res = $con->prepare($sql);
            $res->execute();
            $owner = $res->fetch();
            $email = $owner['email'];
            if($email){
                $newHashed = crypt($password, 'CYRPT_BLOWFISH');
                $sql = "UPDATE Owners SET password='$newHashed' WHERE email='$email'";
                $res = $con->prepare($sql);
                $res->execute();
                header('location: resetcont.php');
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <body>
        <form method="POST" action="">
            <p> Enter a new password for your account. </p>
            <div>
                <label>New Password</label>
                <input type="password" name="new_pass">
            </div>
            <div>
                <label>Reenter New Password</label>
                <input type="password" name="new_pass2">
            </div>
            <div>
                <input name="reset" type="submit">
            </div>
        </form>
    </body>
</html>