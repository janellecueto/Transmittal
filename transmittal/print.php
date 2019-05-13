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
$items = $_POST['items'];   //this is an array of checked items
//NOTE: if "Other" in $items, use $_POST['othertext']

//these should all be arrays
$copies = $_POST['copies'];
$dates = $_POST['dates'];
$numbers = $_POST['numbers'];
$descriptions = $_POST['descriptions'];

$remarks = $_POST['remarks'];

//these are array size 2
$extraComp = $_POST['extraComp'];
$extraName = $_POST['extraName'];
$trOnly = $_POST['trOnly'];
$copyLbl = $_POST['copyLbl'];
$copyEnv = $_POST['copyEnv'];

$dupl = $_POST['dupl'];
$printLblMain = $_POST['printLblMain'];
$printEnvMain = $_POST['printEnvMain'];

$signed = $_POST['signed'];

echo "hello!<br>";
echo "job number: $jobNumber<br>";
echo "client code: $clientCode<br>";
echo "client number: $clientNumber<br>";
echo "date: ".$date->format('Y-m-d')."<br>";
echo "company: $company<br>";
echo "addr1: $addr1<br>";
echo "addr2: $addr2<br>";
echo "city: $city<br>";
echo "state: $state<br>";
echo "zip: $zip<br>";
echo "attention: $attention<br>";
echo "project: $project<br>";

echo "rBtn: $rBtn<br>";
echo "via: $via<br>";

echo "items: <br>";
foreach($items as $item){
    echo "$item, ";
}
echo "<br><br> copies: ".implode(",", $copies)."<br>";
echo "date: ".implode(",", $dates)."<br>";
echo "numbers: ".implode(",", $numbers)."<br>";
echo "descriptions: ".implode(",", $descriptions)."<br>";

echo "remarks: $remarks<br>";

echo "extraComps: ".implode(", ",$extraComp)."<br>";
echo "extraNames: ".implode(", ",$extraName)."<br>";
echo "trOnlys: ".implode(", ",$trOnly)."<br>";
echo "copyLbl: ".implode(", ",$copyLbl)."<br>";
echo "copyEnv: ".implode(", ",$copyEnv)."<br>";

echo "dupl: $dupl<br>";
echo "printLblMain: $printLblMain<br>";
echo "printEnvMain: $printEnvMain<br>";

echo "signed: $signed";