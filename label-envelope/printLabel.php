<?php
/*
 *  Prints to label printer
 * */

//  $debug = true;
function sendLabel($qarr){
    global $debug;

    $len = sizeof($qarr);
    $printerFile = "Q:\\QPRIV\\Label\\d.lbl";
    $handle2 = fopen($printerFile, 'w') or die('Cannot open file: '.$printerFile);

    if($debug){
        echo "inside printLabel, after open printerFile<br>";
        echo "qarr:".implode(", ", $qarr)."<br>";
        fclose($handle2);   //don't print if we're in debug mode
        return; 
    }
    /*
    *  must match format:
    *      label type
    *      job number
    *      attention
    *      company
    *      address line 1
    *      address line 2
    *      city
    *      state
    *      zip
    *      "ParadoxLabelGenerator"
    * */

    fwrite($handle2, "1"); //label type for address labels
    fwrite($handle2, "\n");
    fwrite($handle2, $qarr[0]); //job number
    fwrite($handle2, "\n");
    fwrite($handle2, $qarr[1]); //Attn
    fwrite($handle2, "\n");
    fwrite($handle2, $qarr[2]); //company
    fwrite($handle2, "\n");
    fwrite($handle2, $qarr[3]); //addr 1
    fwrite($handle2, "\n");
    if($len == 7){
        fwrite($handle2, ""); // move city state zip up one line
    }
    else{
        fwrite($handle2, $qarr[4]); //addr2
    }
    fwrite($handle2, "\n");
    fwrite($handle2, $qarr[$len-3]); //city
    fwrite($handle2, "\n");
    fwrite($handle2, $qarr[$len-2]); // state
    fwrite($handle2, "\n");
    fwrite($handle2, $qarr[$len-1]); //zip
    fwrite($handle2, "\n");

    fwrite($handle2, "ParadoxLabelGenerator");

    fclose($handle2);
    //delete a.lbl after prints? idk

    $semfile = "Q:\\QPRIV\\Label\\d.sem"; //should trigger label file to execute then deletes after use
    $handle3 = fopen($semfile, 'w') or die("Error creating sem file: ".$semfile);

    fwrite($handle3, "Attempting to print Label");
    fclose($handle3);
    return; 
}

$q = 0;
if(array_key_exists("q", $_GET)) {
    $q = $_GET['q']; //array of info 
    $qarr = json_decode($q, true);
    sendLabel($qarr);
}

