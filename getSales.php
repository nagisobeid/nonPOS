<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    if (!isset($_SESSION['auth']))
    {
        header("location: login.php");
        exit;
    }
    include_once 'db.php';
    $obj = new DBH;
    $con = $obj->connect();
    $bID = $_SESSION['bid'];

    //select mName, name, itemID, oID from (items natural join contains) natural join menus;

    if(isset($_POST['range'])) 
    {
        $range = $_POST['range'];

        switch($range) {
            case "Today":
                $today = date("Y-m-d");
                $endofday = $today;
                $endofday .= " 23:59:00";
              
                $sql = "SELECT mName, name, itemID, oID, price, placed, quantity FROM (items NATURAL JOIN contains) NATURAL JOIN menus NATURAL JOIN orders where orders.bID = $bID AND orders.placed >= '$today' AND orders.placed <= '$endofday'";
                $res = $con->prepare($sql);
                $res->execute();
                $sales = $res->fetchAll();
                //print_r($sales);
                exit(json_encode($sales));
                break;
            case "This Week":
                $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
                $sunday = date( 'Y-m-d', strtotime( 'sunday this week' ) );
                $sunday .= " 23:59:59";
              
                //$sql = "SELECT price, placed, quantity FROM contains NATURAL JOIN orders where orders.bID = $bID AND orders.placed >= '$monday' AND orders.placed <= '$sunday'";
                $sql = "SELECT mName, name, itemID, oID, price, placed, quantity FROM (items NATURAL JOIN contains) NATURAL JOIN menus NATURAL JOIN orders where orders.bID = $bID AND orders.placed >= '$monday' AND orders.placed <= '$sunday'";
                $res = $con->prepare($sql);
                $res->execute();
                $sales = $res->fetchAll();
                exit(json_encode($sales));
                break;
            case "This Month":
                $thisMonth = date("m",strtotime(date("Y-m-d")));
                $thisYear = date("Y",strtotime(date("Y-m-d")));
                $thisMonthStart = strval($thisYear)  . "-" . strval($thisMonth) . "-" . "01";
                $thisMonthEnd = strval($thisYear)  . "-" . strval($thisMonth) . "-" . "31";
                //$sql = "SELECT price, placed, quantity FROM contains NATURAL JOIN orders where orders.bID = $bID AND orders.placed >= '$thisMonthStart' AND orders.placed <= '$thisMonthEnd'";
                $sql = "SELECT mName, name, itemID, oID, price, placed, quantity FROM (items NATURAL JOIN contains) NATURAL JOIN menus NATURAL JOIN orders where orders.bID = $bID AND orders.placed >= '$thisMonthStart' AND orders.placed <= '$thisMonthEnd'";
                $res = $con->prepare($sql);
                $res->execute();
                $sales = $res->fetchAll();
                exit(json_encode($sales));
                break;
            case "This Year":
                $thisyear = date("Y",strtotime(date("Y-m-d")));
                break;
            }
    }

?> 