<?php
/**
 * This script will echo just the names given a client code. by default, use DEI
 */

include("./info.php");

$clientCode = "DEI";
if(array_key_exists("clientCode", $_GET)) $clientCode = $_GET["clientCode"];
$ret = false;
if(array_key_exists("ret", $_GET)) $ret = $_GET['ret'];

$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->errno){
    echo "<br>Error: ".$conn->error;
    exit();
}

$retArr = [];

$namesQuery = "SELECT `name` FROM tc.clnames WHERE code = '$clientCode' AND `name` NOT LIKE '%Cell%'";
$result = $conn->query($namesQuery);
while($row = $result->fetch_row()){
    $retArr[] = $row[0];
}

if($ret){
    return $retArr;
}
else{
    echo json_encode($retArr);
}
