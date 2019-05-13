<?php
/**
 * this script can be called any time the user types a jn or cc and there are address fields
 */
include("info.php");

$jobNumber = "";
$clientCode = "";

if(array_key_exists("jobNumber", $_GET)) $jobNumber = $_GET['jobNumber'];
if(array_key_exists("clientCode", $_GET)) $clientCode = $_GET['clientCode'];

$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->errno){
    echo "<br>Error: ".$conn->error;
    exit();
}

$query = "SELECT client_code, job_name_1, job_name_2, client_num WHERE jn = '$jobNumber'";

$conn->close();