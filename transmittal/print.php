<?php
/**
 * This file is called on form.submit from ./index.php.
 * Must handle:
 *  - saving data to transmittal table
 *  - printing labels
 *  - printing envelopes
 *  - printing copies for CopyTo entries
 *  - printing duplicates
 */

require_once("../assets/php/info.php");
include("../assets/php/current.php");
include_once("../assets/php/createPDF.php");
include_once("../label-envelope/printLabel.php");
include_once("../label-envelope/printEnvelope.php");

$debug = false;  //global flag for debugging :)

//NOTE: since this script only gets called on form submit for basically the entire page of ./index.php, we know that all
//      of these fields will be in the POST array
$jobNumber = $_POST['jobNumber'];
$clientCode = $_POST['clientCode'];
$clientNumber = $_POST['clientNumber'];

$date = new DateTime($_POST['date']);
$company = $_POST['company'];
$addr1 = $_POST['addr1'];
$addr2 = $_POST['addr2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$attention = $_POST['attention'];
$project = $_POST['project'];
$rBtn = $_POST['rBtn'];
$via = $_POST['via'];
$signed = $_POST['signed'];
$remarks = $_POST['remarks'];

//below are arrays
$items = $_POST['items'];   //NOTE: if "Other" in $items, use $_POST['othertext']
$copies = $_POST['copies'];
$dates = $_POST['dates'];
$numbers = $_POST['numbers'];
$descriptions = $_POST['descriptions'];

//below are not required for pdf creation 
$extraComp = $_POST['extraComp'];
$extraCode = $_POST['extraCode'];
$extraName = $_POST['extraName'];
$trOnly = (array_key_exists("trOnly", $_POST) ? $_POST['trOnly'] : 0);        //these are arrays (name="<param>[]") of check boxes :/
$copyLbl = (array_key_exists("copyLbl", $_POST) ? $_POST['copyLbl'] : 0);
$copyEnv = (array_key_exists("copyEnv", $_POST) ? $_POST['copyEnv'] : 0);

$save = intval($_POST['save']);
$dupl = intval($_POST['dupl']);
if(!$dupl) $dupl = 1;
$printLblMain = (array_key_exists("printLblMain", $_POST) ? $_POST['printLblMain'] : 0);
$printEnvMain = (array_key_exists("printEnvMain", $_POST) ? $_POST['printEnvMain'] : 0);

if($debug){
    echo "$jobNumber<br>";
}

/***********************************************************************************************************************
 * Write to DB
 */
$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->connect_errno){
    echo "<br>Error: ".$conn->error;
    exit();
}


if($save){
    $mainQuery = "INSERT INTO tc.$transTbl (`Date`, `Code`, Company, Addr1, Addr2, Project, City, State, Zip, Jn, ";
    $values = "VALUES( '".$date->format("Y-m-d")."', '$clientCode', '$company', '$addr1', '$addr2', '$project', '$city', '$state', '$zip', '$jobNumber', ";

    $mainQuery .= "`Client num`, Attention, `$rBtn`, Via, ";
    $values .= "'$clientNumber', '$attention', 1, '$via', ";

    foreach($items as $v){
        if(strpos($v, "Other") !== false){
            $mainQuery .= "Other, `What other`, ";
            $values .= "1, '".$_POST['otherText']."', ";
        }
        else{
            $mainQuery .= "`$v`, ";
            $values .= "1, ";
        }
    }

    //$copies, $numbers, $dates, and $descriptions will all be the same length. go through copies and increment i to
    //get the correct index for the other three.
    $i = 1;     //NOTE: in the db table, this sub-table starts with "C1" "D1" "Nn1" "Desc1" through 8
    foreach($copies as $c){
        $mainQuery .= "C$i, D$i, Nn$i, Des$i, ";
        //NOTE: copies and numbers are numeric, dates and descriptions are strings
        $values .= "$c, '".$dates[$i-1]."', ".$numbers[$i-1].", '".str_replace("'", '"', $descriptions[$i-1])."', ";
        $i++;
    }

    if($remarks){
        $mainQuery .= "Remarks, ";
        $values .= "'".str_replace("'", '"', $remarks)."', ";
    }
    if($extraComp[0]){
        $mainQuery .= "`Copy to1`, ";
        $values .= "'$extraComp[0]', ";
        if($extraName[0]){
            $mainQuery .= "`Copy_1_who`, ";
            $values .= "'$extraName[0]', ";
        }
    }
    if($extraComp[1]){
        $mainQuery .= "`Copy to2`, ";
        $values .= "'$extraComp[1]', ";
        if($extraName[1]){
            $mainQuery .= "`Copy_2_who`, ";
            $values .= "'$extraName[1]', ";
        }
    }

    $mainQuery .= "Signed) ";
    $values .= "'$signed')";

    $mainQuery .= $values;

    if(!$debug) {
        if (!$conn->query($mainQuery)) {
            echo "Error: " . $conn->error . "<br>$mainQuery<br><b>Form data NOT saved to db.</b>";
            exit;
        }
    }

}   //end if($save)

// $serialNo = findCurrent($conn, $transTbl);   //it appears that we don't print this anymore? i forget (JC 05.28.2019)
$conn->close();

// $mainPdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// transmittalPDF($mainPdf);
transmittalPDF();

$q = [];
$q[] = $jobNumber;
$q[] = $attention;
$q[] = $company;
$q[] = $addr1;
if($addr2) $q[] = $addr2;
$q[] = $city;
$q[] = $state;
$q[] = $zip;

// $_GET['q'] = json_encode($q);           //print envelope and print label use the same array structure and q as parameter
if($printLblMain){
    sendLabel($q);
    echo "$company address info sent to label printer<br>";
}
if($printEnvMain){
    array_shift($q);    //removes first element, jobNumber, from q array
    printEnvelope($q);
    echo "$company address info sent to envelope printer<br>";
}

$jn = $jobNumber;   //hold value for $jobNumber in $jn because fillAddress resets jobNumber :/
//now check for extras
for($i = 0; $i<2; $i++){
    if($extraComp[$i]){

        $_GET['value'] = $extraComp[$i];
        $_GET['flag'] = "company";
        $_GET['ret'] = 1;
        $extraInfo = include("../assets/php/fillAddress.php");
        if(!$extraInfo){
            echo "No client code for ".$extraComp[$i]."?";
            //if fillAddress.php returns 0, there's no client matching client code/company
            continue; //skip this one, but check next one
        }

        $q = []; //reset $q
        $q[] = $jn;
        $q[] = $attention = $extraName[$i];
        $q[] = $company = $extraComp[$i];

        $q[] = $addr1 = $extraInfo['addr1'];
        $addr2 = "";
        if($extraInfo['addr2']) $q[] = $addr2 = $extraInfo['addr2'];
        $q[] = $city = $extraInfo['city'];
        $q[] = $state =  $extraInfo['state'];
        $q[] = $zip = $extraInfo['zip'];
        
        if($debug){
            echo "jobNumber: $jn<br>";
        }
        
        // $copyPdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        transmittalPDF();

        if($copyLbl && in_array('lbl'.($i+1), $copyLbl)){
            sendLabel($q);
            echo "$company address info sent to label printer<br>";
        }
        if($copyEnv && in_array("env".($i+1), $copyEnv)){
            array_shift($q);    //removes first element, jobNumber, from q array
            printEnvelope($q);
            echo "$company address info sent to envelope printer<br>";
        }

    }
}
