<?php
    header("Content-type: application/json; charset=utf-8");
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

     function loadOpenOrders($orderType, $con, $bID) {
       // $orderType = strval($orderType);
        $sql = "SELECT username, oID from orders WHERE type = $orderType and bID = $bID and completed IS NULL";
        $res = $con->prepare($sql);
        $res->execute();
        $orders = $res->fetchAll();
        //print_r($orders);
        return $orders;
     }
    
     if(isset($_GET)) {
        $type = 0;
        switch($_GET['orderType']) {
            case "Dine-In":
                $type = 1;
                break;
            case "Take-Out":
                $type = 2;
                break;
            case "Call-In":
                $type = 3;
                break;
            case "Drive-Thru":
                $type = 4;
                break;
        }
        
        $openOrders = loadOpenOrders($type, $con, $bID);
        exit(json_encode($openOrders));
     }
?>