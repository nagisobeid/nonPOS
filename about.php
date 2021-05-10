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
        <body>
            <section> 
                <article>
                    <div class="w3-content" style="max-width:1000px">
                        <div class"w3-col m6 w3-padding-large">
                            <h3 class="w3-center">Why non Point Of Sales?</h3><br>
                            <h5 class="w3-large">nonPOS was established to give small businesses for taking orders and make transactions. This system will make small business lives moving much quicker.</h5><br>
                        </div>
                    </div>
                    <br><br><br><br><br><br>
                        <div class="w3-col m6 w3-padding-large w3-hide-small">
                            <img src="./images/nonposabout.png" alt="aboutpos" width="940" height="600">
                        </div>
                    <br><br><br><br><br><br>

                    <h3>Have your small business succeed</h3>
                    <p>You can easily run your business<br>
                       for your team and your customers</p><br><br><br>

                    <br><br><br><br><br><br>
                    <div class"w3-container w3-padding-64 w3-dark-gray" id="contact">
                        <h1>Contact</h1><br>
                        <p>Find out more if nonPOS is right for your business</p>
                        <p class="w3-text-white w3-large"><b>Address: nonPOS inc, 63rd None St, 93336 California, CA</b></p>
                        <p>Phone: (611) 336-3636</p>
                        <p>Email: nonPOS36@nonmail.com<p>
                        <p>Or fill out a <a href="contactform.html">Contact Form</a></p>
                    </div>
                </article>
            </section>
        </body>
    </div>
</div>
</html>
