<?php
    session_start();
    if (!isset($_SESSION['auth']))
    {
        header("location: login.php");
        exit;
    }
    $src = pin.php;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel='stylesheet' href="about.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel='stylesheet' href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
        <header>
            <div id="main-bar">
                <img id="logo" src="./images/logo2.png"></img>
                <button onclick="document.location='home.php'" type="button" id="idBtnHome" class="btn btn-link">Home</button>
                <button onclick="document.location='about.php'" type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>

            </div>
        </header>
<div class="mainContainer">
    <div id="mid-section" style="padding-bottom: 0px;">

    <div id="jumb" class="jumbotron" style="box-shadow: 5px 10px; height: 360px; overflow:auto;">
        <body>
            <section>
                <article>
                    <h3>Why non Point Of Sales?</h3>
                    <p>nonPOS was established to give small businesses for taking orders<br>
                       and make transactions. This system will make small business lives<br>
                       moving much quicker.</p><br>

                    <h3>Have your small business succeed</h3>
                    <p>You can easily run your business<br>
                       for your team and your customers</p>
                </article>
            </section>
        </body>
    </div>
</div>
</html>
