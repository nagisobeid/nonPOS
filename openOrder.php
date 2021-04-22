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

     function openOrder($orderID, $con, $bID) {
       // $orderType = strval($orderType);
        $items = "";
        $mods = "";
        $total = "";
        $sql = "SELECT username, oID from orders WHERE type = $orderType and bID = $bID and completed IS NULL";
        $res = $con->prepare($sql);
        $res->execute();
        $orders = $res->fetchAll();
        //print_r($orders);
        return $order;
     }
    
     if(isset($_GET)) {
        $order = openOrder($orderID, $con, $bID);
        exit(json_encode($order));
     }
?>