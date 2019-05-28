<?php
/**
 * this script can be called any time the user types a jn or cc and there are address fields
 */
include("info.php");

$jobNumber = "";
$clientCode = "";

//if(array_key_exists("jobNumber", $_GET)) $jobNumber = $_GET['jobNumber'];
//if(array_key_exists("clientCode", $_GET)) $clientCode = $_GET['clientCode'];
$flag = $_GET['flag'];
$value = $_GET['value'];
$ret = false;
if(array_key_exists("ret", $_GET)) $ret = true;

if($flag == "jobNumber") $jobNumber = $value;
else if($flag == "clientCode") $clientCode = $value;

$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->errno){
    echo "<br>Error: ".$conn->error;
    exit();
}

$retArr = [];

if($jobNumber) {
    $query = "SELECT client_code, job_name_1, job_name_2, client_num FROM tc.job_name WHERE jn = '$jobNumber'";

    $result = $conn->query($query);
    $row = $result->fetch_array();
    $clientCode = $row['client_code'];

    $retArr['clientCode'] = $clientCode;
    $retArr['jn1'] = $row['job_name_1'];
    $retArr['jn2'] = $row['job_name_2'];
    $retArr['clientNumber'] = $row['client_num'];
}

$query = "SELECT company, addr1, addr2, city, state, zip, fax FROM tc.clients WHERE code = '$clientCode'";
$namesQuery = "SELECT `name` FROM tc.clnames WHERE code = '$clientCode' AND `name` NOT LIKE '%Cell%'";
$result = $conn->query($query);
$row = $result->fetch_array();

$retArr['company'] = $row['company'];
$retArr['addr1'] = $row['addr1'];
$retArr['addr2'] = $row['addr2'];
$retArr['city'] = $row['city'];
$retArr['state'] = $row['state'];
$retArr['zip'] = $row['zip'];
$retArr['fax'] = $row['fax'];

$names = $conn->query($namesQuery);

$nameArr = [];
while($nrow = $names->fetch_array()){
    $nameArr[] = $nrow['name'];
}
$retArr['names'] = $nameArr;

$conn->close();

if($ret){
    return $retArr;
} else{
    echo json_encode($retArr);
}