<?php

/**
 * This script is called when filling in initials and user expects client name to auto complete 
 */
include("info.php");

 $initials = $_GET['initials'];
 $code = $_GET['clientCode'];

 $conn = new mysqli($host, $user, $password, $defaultTbl);
 if($conn->errno){
     echo "Error: ".$conn->error;
     exit();
 }

$query = "SELECT `name` FROM $defaultTbl.clnames WHERE initials = '$initials' AND code = '$code'";
$result = $conn->query($query);
if($row = $result->fetch_row()){
    echo $row[0];
}
else{
    echo "0";
}

 $conn->close();