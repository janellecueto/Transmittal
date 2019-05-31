<?php
/**
 * Displays or returns plotting prices
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
$rows = $result->fetch_all();        //fetch all returns all rows from table. there should only be 18 for ss1 - ss9

if($ret === 1){
    echo json_encode($rows);
    exit;
} else if($ret === 2){
    return $rows;
    exit;   //i don't think i need the exit here?
}
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../assets/css/bootstrap-reboot.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-grid.css">
    <link rel="stylesheet" href="../assets/css/mainStyle.css">
    <title>Current Plotting Prices</title>
</head>
<body>
    <div class="container">
        <table class="table table-sm">
            <thead class="sticky-top">
                <th scope="col">Size</th>
                <th scope="col">Media</th>
                <th scope="col">Cost - Color</th>
                <th scope="col">Cost - No color</th>
            </thead>          
            <tbody>
<?php
            $len = sizeof($rows);
            for($i = 0; $i<$len-1; $i+=2){       //this table is organized every other row x_______x don't know why 
                echo "<tr>";
                echo "<td>".$rows[$i][0]."</td>";
                echo "<td>".$rows[$i][1]."</td>";
                echo "<td>".$rows[$i][3]."</td>";
                echo "<td>".$rows[$i+1][3]."</td>";
                echo "</tr>";
            }
?>
            </tbody>
        </table>
    </div>
</body>
</html>


