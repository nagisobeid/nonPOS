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
        if($password == $password2){
            $sql = "SELECT email FROM Owners WHERE token='$token'";
            $res = $con->prepare($sql);
            $res->execute();
            $owner = $res->fetch();
            $email = $owner['email'];
            if($email){
                $newHashed = crypt($password, 'CRYPT_BLOWFISH');
                $sql = "UPDATE Owners SET password='$newHashed' WHERE email='$email'";
                $res = $con->prepare($sql);
                $res->execute();
                echo '<script>alert("Password reset correctly."); window.location="login.php";</script>';
            }
        }  
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="reset.css">
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
                <input type="password" name="new_pass" placeholder="New Password" require>
                <input type="password" name="new_pass2" placeholder="Reenter Password" require>
                <input name="reset" type="submit">
            </form>
        </div>
    </body>
</html>