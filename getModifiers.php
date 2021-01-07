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

     function loadModifiers($itemID, $con) {
        $sql = "SELECT extraKey from items WHERE itemID = $itemID";
        $res = $con->prepare($sql);
        $res->execute();
        $modifiers = $res->fetch();
        $parsed = explode(',', $modifiers[0]);
        array_pop($parsed);
        $modifiersList = [];
        for ($x = 0; $x < count($parsed); $x++) {
            $sql = "SELECT name from extras WHERE exID = $parsed[$x]";
            $res = $con->prepare($sql);
            $res->execute();
            $name = $res->fetch();
            array_push($modifiersList, $name[0]);
        }
        return $modifiersList;
     }
     if(isset($_GET)) {
        $itemID = $_GET['q'];
        $m = loadModifiers($itemID,$con);
        exit(json_encode($m));
     }
?>