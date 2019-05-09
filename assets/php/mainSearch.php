<?php

require_once("../../info.php");

$startDate = "";
if(array_key_exists('startDate', $_POST)) $startDate = $_POST['startDate'];
$endDate = "";
if(array_key_exists('endDate', $_POST)) $endDate = $_POST['endDate'];
$jobNumber = "";
if(array_key_exists('jobNumber', $_POST)) $jobNumber = $_POST['jobNumber'];
$clientCode = "";
if(array_key_exists('clientCode', $_POST)) $clientCode = $_POST['clientCode'];
$signature = "";
if(array_key_exists('signature', $_POST)) $signature = $_POST['signature'];
$invoiceNo = "";
if(array_key_exists('invoiceNo', $_POST)) $invoiceNo = $_POST['invoiceNo'];

$type = $_POST['type'];

$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->errno){
    print "<br>Error: ".$conn->error;
    exit();
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
echo "ya made it to this page! woo!<br>";
echo "$startDate";
    ?>
</body>
</html>