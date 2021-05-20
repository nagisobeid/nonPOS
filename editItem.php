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

     function editItem($itemID, $con) {
        $sql = "SELECT extraKey from items WHERE itemID = $itemID";
        $res = $con->prepare($sql);
        $res->execute();

        $sql = "UPDATE from extras WHERE itemID = $itemID";
        $res = $con->prepare($sql);
        $res->execute();

        $sql = "UPDATE from items WHERE itemID = $itemID";
        $res = $con->prepare($sql);
        $res->execute();
        
        return $modifiersList;
     }

     if(isset($_POST['submit'])) {
        if ($_POST['submit'] == 'Edit Item') {
            editItem($con);
            echo "<meta http-equiv='refresh' content='0'>";
        }
     }

     if(isset($_GET)) {
        $itemID = $_GET['e'];
        $m = loadModifiers($itemID,$con);
        exit(json_encode($m));
     }
?>
