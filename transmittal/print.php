<?php
/**
 * This file is called on form.submit from ./index.php.
 * Must handle:
 *  - printing labels
 *  - printing envelopes
 *  - saving copies for CopyTo entries
 *  - printing duplicates
 */

require_once('../../tcpdf6/tcpdf.php');
require_once('../../tcpdf6/examples/lang/eng.php');
include("../info.php");

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
$dupl = $_POST['dupl'];
$printLblMain = $_POST['printLblMain'];
$printEnvMain = $_POST['printEnvMain'];
$signed = $_POST['signed'];
$remarks = $_POST['remarks'];

//below are all arrays
$items = $_POST['items'];   //NOTE: if "Other" in $items, use $_POST['othertext']

$copies = $_POST['copies'];
$dates = $_POST['dates'];
$numbers = $_POST['numbers'];
$descriptions = $_POST['descriptions'];
$extraComp = $_POST['extraComp'];
$extraName = $_POST['extraName'];
$trOnly = $_POST['trOnly'];
$copyLbl = $_POST['copyLbl'];
$copyEnv = $_POST['copyEnv'];


//echo "hello!<br>";
//echo "job number: $jobNumber<br>";
//echo "client code: $clientCode<br>";
//echo "client number: $clientNumber<br>";
//echo "date: ".$date->format('Y-m-d')."<br>";
//echo "company: $company<br>";
//echo "addr1: $addr1<br>";
//echo "addr2: $addr2<br>";
//echo "city: $city<br>";
//echo "state: $state<br>";
//echo "zip: $zip<br>";
//echo "attention: $attention<br>";
//echo "project: $project<br>";
//
//echo "rBtn: $rBtn<br>";
//echo "via: $via<br>";
//
//echo "items: <br>";
//foreach($items as $item){
//    echo "$item, ";
//}
//echo "<br><br> copies: ".implode(",", $copies)."<br>";
//echo "date: ".implode(",", $dates)."<br>";
//echo "numbers: ".implode(",", $numbers)."<br>";
//echo "descriptions: ".implode(",", $descriptions)."<br>";
//
//echo "remarks: $remarks<br>";
//
//echo "extraComps: ".implode(", ",$extraComp)."<br>";
//echo "extraNames: ".implode(", ",$extraName)."<br>";
//echo "trOnlys: ".implode(", ",$trOnly)."<br>";
//echo "copyLbl: ".implode(", ",$copyLbl)."<br>";
//echo "copyEnv: ".implode(", ",$copyEnv)."<br>";
//
//echo "dupl: $dupl<br>";
//echo "printLblMain: $printLblMain<br>";
//echo "printEnvMain: $printEnvMain<br>";
//
//echo "signed: $signed";

/***********************************************************************************************************************
 * Write to DB
 */

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

echo "hello!<br>";
echo "$mainQuery";



/***********************************************************************************************************************
 * TCPDF helper functions
 */

/**
 * This function adds items under 'Via'
 * @param $arr - $items array
 * @param $doc - main pdf doc
 */
function addMailCheckBoxes($arr, $doc){
    $doc->Text(20, 89, "___ Shop drawings");            //initialize item blanks
    $doc->Text(20, 96, "___ Copy of letter");
    $doc->Text(64, 89, "___ Prints");
    $doc->Text(64, 96, "___ Change order");
    $doc->Text(109, 89, "___ Plans");
    $doc->Text(109, 96, "___ Samples");
    $doc->Text(148, 89, "___ Specifications");
    $doc->Text(20, 103, "___ Other:");

    $map = [
        "Shop drawings" => [21, 89], "Copy of letter" => [21, 96], "Prints" => [65, 89], "Change order" => [65, 96],
        "Plans" => [110, 89], "Samples" => [110, 96], "Specifications" => [149, 89]];

    foreach($arr as $a){                                //for every item that matches one of our check boxes, mark the item
        if(array_key_exists($a, $map)) $doc->Text($map[$a][0], $map[$a][1], " X");
        else{ $doc->Text(21, 103, " X"); $doc->Text(40, 103, $a);}
    }
}

/**
 * Adds rows to the Copies/Date/Number/Description table
 * @param $cArr     - copies[]
 * @param $dArr     - dates[]
 * @param $nArr     - numbers[]
 * @param $descArr  - descriptions[]
 * @param $doc      - main pdf doc
 */
function addToTable($cArr, $dArr, $nArr, $descArr, $doc){
    //table begins at (15, 116) to (197,160)
    //copies: 15 sp, date: 20sp, number: 15sp, description: rest
    $doc->SetFont('helvetica', '', 9);
    $y = 123;
    $len = sizeof($cArr); //all arrays should be same size???
    for($i = 0; $i<$len; $i++){
        //iterate thru elements of all table arrays
        $doc->Text(20, $y, $cArr[$i]);
        $doc->Text(32, $y, $dArr[$i]);
        $doc->Text(57, $y, $nArr[$i]);
        $doc->Text(71, $y, $descArr[$i]);
        $y += 4;
    }
}

/**
 * THIS FUNCTION NO LONGER GETS CALLED. The Transmitted As section has been removed
 * Adds Transmitted As items to the Transmitted As section before Remarks
 * @param $x    - start x pos
 * @param $y    - start y pos
 * @param $arr  - array for AS TRANSMITTED list
 * @param $doc  - main pdf doc
 */
function addCheckBoxes($x, $y, $arr, $doc){
    $doc->SetFont('courier', '', 11);
    //breaks into 2 cols of 7 items
    //$doc->Text($x, $y, $arr[0]);
    $ypos = $y;
    $len = sizeof($arr);
    for($i=0; $i<7; $i++){
        if(!$arr[$i]){
            break;
        }
        $doc->Text($x, $ypos, "-");
        $doc->Text($x+3, $ypos, $arr[$i]);
        $ypos += 4;;
    }
    $ypos = $y;
    $x += 80;
    for($i=7; $i<$len; $i++){
        if(!$arr[$i]){
            break;
        }
        $doc->Text($x, $ypos, "-");
        $doc->Text($x+3, $ypos, $arr[$i]);
        $ypos += 4;;
    }
}
