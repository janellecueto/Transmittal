<?php
/**
 * Sets current Serialno/InvoiceNo for all three tables.
 */
include("info.php");
$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->connect_errno){
    echo "<br>Error: ".$conn->error;
    exit();
}

$transSerial = $faxSerial = $billInvoice = 0;

function findCurrent($conn, $tbl){
    $query = "SELECT * FROM tc.$tbl ORDER BY 1 DESC LIMIT 1";
    $result = $conn->query($query);
    $row = $result->fetch_row();
    return floatval($row[0]);
}

$transSerial = findCurrent($conn, $transTbl) +1;
$faxSerial = findCurrent($conn, $faxTbl) +1;
$billInvoice = findCurrent($conn, $pbillTbl) +1;

$conn->close();