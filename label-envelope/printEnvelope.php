<?php
/**
 * Prints to reception printer using PCL for envelope formatting 
 */

$printerFile = "\\\\Server2008\ReceptionPrinter";
$handle2 = fopen($printerFile, 'w') or die('Cannot open file: '.$printerFile);

$esc = chr(27); //escape key ascii

function printEnvelope($qarr){
    /*
    * Below code only inserts new lines after each addr element
    * */
    $len = sizeof($qarr);
    /*
    *  PCL escape code commands for envelope printing
    * */
    fwrite($handle2, $esc);
    fwrite($handle2, "E"); //printer reset cmd
    fwrite($handle2, $esc);
    fwrite($handle2, "%1A");
    fwrite($handle2, $esc);
    fwrite($handle2, "&l0s6h81a1o1X"); //sets primary font 

    fwrite($handle2, $esc);
    fwrite($handle2, "&a8r40C"); //cursor position
    fwrite($handle2, $esc);
    fwrite($handle2, "&a40l");  //set left margin

    fwrite($handle2, $esc);
    fwrite($handle2, "(8U");
    fwrite($handle2, $esc);
    fwrite($handle2, "(s1p12v0s0b4148T"); //style code for addr

    //insert address
    for($i = 0; $i<$len; $i++){
        if ($i >= $len - 3){
            //format the last 3 elements differently
            fwrite($handle2, $qarr[$i]);
            fwrite($handle2, ", ");
            break;
        }
        //adds newline char after each element of array
        fwrite($handle2, (string)$qarr[$i]);
        fwrite($handle2, "\r\n");
    }
    //after break; in for loop above:
    fwrite($handle2, $qarr[$len - 2]);
    fwrite($handle2, "  ");
    fwrite($handle2, $qarr[$len - 1]);
    //end address

    fwrite($handle2, $esc);
    fwrite($handle2, "(10U");
    fwrite($handle2, $esc);
    fwrite($handle2, "(s0p10h12v0s0b3T");
    fwrite($handle2, $esc);
    fwrite($handle2, "&a23r50C");
    fwrite($handle2, $esc);
    fwrite($handle2, "(15Y");
    fwrite($handle2, $esc);
    fwrite($handle2, "(s1p12v0s0b0T");
    fwrite($handle2, "*");
    fwrite($handle2, $qarr[$len-1]);
    fwrite($handle2, "*");
    fwrite($handle2, $esc);
    fwrite($handle2, $esc);     //not sure what all of this does0
    fwrite($handle2, "E"); //reset printer

    fclose($handle2);
}

$q = 0;
if(array_key_exists("q", $_GET)){
    $q = $_GET['q']; //array of info
    $qarr = json_decode($q, true);
    printEnvelope($qarr);
} 