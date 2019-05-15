<?php
/**
 * Displays or returns plotting prices
 *
 * NOTE: the number of sizes is hardcoded (9). This may come up in the future wherein you will
 *       have to update ./index for sheet size option and what not
 */

include("../assets/php/info.php");

$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->errno){
    echo "<br>Error: ".$conn->error;
    exit();
}

$ret = false;
if(array_key_exists("ret", $_GET)) $ret = $_GET['ret'];

$query = "SELECT * FROM tc.mcosts_test";
$result = $conn->query($query);
$row = $result->fetch_all();        //fetch all returns all rows from table. there should only be 9

if($ret){
    echo json_encode($row);
    exit;
}
$conn->close();

?>


