<?php
    session_start();
    if (!isset($_SESSION['auth']))
    {
        header("location: login.php");
        exit;
    }
    include_once 'db.php';
	$obj = new DBH;
    $con = $obj->connect();
    

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="sales.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/JavaScript">

        //PIE CHART
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Hour', 'Sales'],
          ['6:00 AM', 1000],
          ['7:00 AM', 1170],
          ['8:00 AM', 660],
          ['9:00 AM', 1030],
          ['10:00 AM', 1000],
          ['11:00 AM', 1170],
          ['12:00 PM', 660],
          ['1:00 PM', 1030],
          ['2:00 PM', 1000],
          ['3:00 PM', 1170],
          ['4:00 PM', 660],
          ['5:00 PM', 1030],
          ['6:00 PM', 660],
          ['7:00 PM', 1030],
          ['8:00 PM', 1000],
          ['9:00 PM', 1170],
          ['10:00 PM', 660],
          ['11:00 PM', 1030],
          ['12:00 AM', 1030],
          ['1:00 AM', 1030],
          ['2:00 AM', 1000],
          ['3:00 AM', 1170],
          ['4:00 AM', 660],
          ['5:00 AM', 1030],
        ]);

        var options = {
            legend: { position: 'none' },
          chart: {
            title: 'Sales Data',
            subtitle: 'Sales: 2014-2017',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
        //END

        $(document).ready(function() {
                   
            $(document).on('click', '.nav-link', function() {
                $(".nav-link").removeClass("selected");
                $(this).addClass("selected");
            });
        });
        
	</script>
</head>
 
<body>
    <header>
        <div id="main-bar">
            <img id="logo" src="./images/logo2.png"></img>
            <button onclick="document.location='home.php'" type="button" id="idBtnHome"
                class="btn btn-link">Home</button>
            <button type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
        </div>
    </header>
    <div class="btn-group" role="group" aria-label="Basic example" id="salesOptions">
        <button type="button" class="btn btn-success optionBtn noRadius">Today</button>
        <button type="button" class="btn btn-success optionBtn noRadius">This Week</button>
        <button type="button" class="btn btn-success optionBtn noRadius">This Month</button>
        <button id="last" type="button" class="btn btn-success optionBtn noRadius">This Year</button>
    </div>
    <div style="width: 100%;">
    <div id="columnchart_material" style="width: 99%; height: 200px;"></div>
    </div>
    <!--
    <div class="row flex-nowrap" style="width: 100% !important; height: 100% !important;">
        <div class=" col-md-12 col-xs-12 col-lg-12 bg-light" style="height: 50px !important;" id="sideCol">
            <div class="nav" id="idOptions">
                <a class="nav-lin selected" id="idToday">Today</a>
                <a class="nav-lin" id="idThisWeek">This Week</a>
                <a class="nav-lin" id="idThisMonth">This Month</a>
                <a class="nav-lin" id="idThisYear">This Year</a>
            </div>
        </div>
    </div>
    -->

</body>
</html> 