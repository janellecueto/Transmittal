<?php
/**
 * this script can be called any time the user types a jn or cc and there are address fields
 */
include("info.php");

$jobNumber = $clientCode = $company = "";

$flag = $_GET['flag'];
$value = $_GET['value'];
$ret = false;
if(array_key_exists("ret", $_GET)) $ret = true;

if($flag == "jobNumber") $jobNumber = $value;
else if($flag == "clientCode") $clientCode = $value;
else if($flag == "company") $company = $value;

$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->errno){
    echo "<br>Error: ".$conn->error;
    exit();
}

$retArr = [];

if($jobNumber) {
    $query = "SELECT client_code, job_name_1, job_name_2, client_num FROM tc.job_name WHERE jn = '$jobNumber'";

    $result = $conn->query($query);
    if($row = $result->fetch_array()){
        $clientCode = $row['client_code'];

        $retArr['clientCode'] = $clientCode;
        $retArr['jn1'] = $row['job_name_1'];
        $retArr['jn2'] = $row['job_name_2'];
        $retArr['clientNumber'] = $row['client_num'];
    }
    else{
        echo "Error: No info associated with job number: $jobNumber<br>";
        exit;
    }
}

$query = "SELECT company, addr1, addr2, city, state, zip, fax FROM tc.clients WHERE ";

if($company) $query .= "company = '$company'";
else $query .= "code = '$clientCode'";

$namesQuery = "SELECT `name` FROM tc.clnames WHERE code = '$clientCode' AND `name` NOT LIKE '%Cell%'";
$result = $conn->query($query);

if($row = $result->fetch_array()){

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
}
else{
    if($ret){
        echo "$clientCode or $company does not exist";
        return 0;
    } else{
        echo "Error: client code $clientCode does not exist<br>";
        exit;
    }
}

$conn->close();

if($ret && sizeof($retArr)){
    return $retArr;
} else{
    echo json_encode($retArr);
}