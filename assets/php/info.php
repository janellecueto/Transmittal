<?php
$host = "SERVER2008";
$user = "TC";
$password = "Dickerson1";
$defaultTbl = "tc";

$transTbl = "trans";
$transTblOld = "trans91_18";
$faxTbl = "faxtr";
$faxTblOld = "faxtr94_17";
$pbillTbl = "pbill";
$pbillTblOld = "pbill01_18";

$domain = $_SERVER['REMOTE_ADDR'];
$isJay = $jsRick = $isAdmin = false;
if (strpos($domain, ".1.28") !== false) $isJay = true;
else if (strpos($domain, ".1.36") !== false) $isRick = true;
else if (strpos($domain, ".1.21") !== false || strpos($domain, ".1.22") !== false || strpos(gethostbyaddr($domain), "janelle") !== false){
    $isAdmin = true;
}
