<?php
/**
 * This file is called on form.submit from ./index.php.
 * Must handle:
 *  - printing labels
 *  - printing envelopes
 *  - saving copies for CopyTo entries
 *  - printing duplicates
 */

// require_once('../../tcpdf6/tcpdf.php');
// require_once('../../tcpdf6/examples/lang/eng.php');
require_once("../assets/php/info.php");
include("../assets/php/current.php");
include("createPDF.php");

$debug = false;  //flag for debugging :)
//if($debug){
//    header('Content-type: application/pdf');
//    header('Content-disposition: inline');
//}

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
$trOnly = (array_key_exists("trOnly", $_POST) ? $_POST['trOnly'] : [0]);        //these are arrays (name="<param>[]") of check boxes :/
$copyLbl = (array_key_exists("copyLbl", $_POST) ? $_POST['copyLbl'] : [0]);
$copyEnv = (array_key_exists("copyEnv", $_POST) ? $_POST['copyEnv'] : [0]);

$save = intval($_POST['save']);
$dupl = intval($_POST['dupl']);
if(!$dupl) $dupl = 1;
$printLblMain = (array_key_exists("printLblMain", $_POST) ? $_POST['printLblMain'] : 0);
$printEnvMain = (array_key_exists("printEnvMain", $_POST) ? $_POST['printEnvMain'] : 0);

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

    // echo "hello!<br>";
    // echo "$mainQuery";

    if(!$debug) {
        if (!$conn->query($mainQuery)) {
            echo "Error: " . $conn->error . "<br>$mainQuery<br>Form data not saved to db.";
            exit;
        }
    }

}   //end if($save)

// $serialNo = findCurrent($conn, $transTbl);   //it appears that we don't print this anymore? i forget (JC 05.28.2019)
$conn->close();
// /***********************************************************************************************************************
//  * TCPDF helper functions
//  */

// /**
//  * This function adds items under 'Via'
//  * @param $arr - $items array
//  * @param $doc - main pdf doc
//  */
// function addMailCheckBoxes($arr, $doc){
//     $doc->Text(20, 89, "___ Shop drawings");            //initialize item blanks
//     $doc->Text(20, 96, "___ Copy of letter");
//     $doc->Text(64, 89, "___ Prints");
//     $doc->Text(64, 96, "___ Change order");
//     $doc->Text(109, 89, "___ Plans");
//     $doc->Text(109, 96, "___ Samples");
//     $doc->Text(148, 89, "___ Specifications");
//     $doc->Text(20, 103, "___ Other:");

//     $map = [
//         "Shop drawings" => [21, 89], "Copy of letter" => [21, 96], "Prints" => [65, 89], "Change order" => [65, 96],
//         "Plans" => [110, 89], "Samples" => [110, 96], "Specifications" => [149, 89]];

//     foreach($arr as $a){                                //for every item that matches one of our check boxes, mark the item
//         if(array_key_exists($a, $map)) $doc->Text($map[$a][0], $map[$a][1], " X");
//         else{ $doc->Text(21, 103, " X"); $doc->Text(40, 103, $a);}
//     }
// }

// /**
//  * Adds rows to the Copies/Date/Number/Description table
//  * @param $cArr     - copies[]
//  * @param $dArr     - dates[]
//  * @param $nArr     - numbers[]
//  * @param $descArr  - descriptions[]
//  * @param $doc      - main pdf doc
//  */
// function addToTable($cArr, $dArr, $nArr, $descArr, $doc){
//     //table begins at (15, 116) to (197,160)
//     //copies: 15 sp, date: 20sp, number: 15sp, description: rest
//     $doc->SetFont('helvetica', '', 9);
//     $y = 123;
//     $len = sizeof($cArr); //all arrays should be same size???
//     for($i = 0; $i<$len; $i++){
//         //iterate thru elements of all table arrays
//         $doc->Text(20, $y, $cArr[$i]);
//         $doc->Text(32, $y, $dArr[$i]);
//         $doc->Text(57, $y, $nArr[$i]);
//         $doc->Text(71, $y, $descArr[$i]);
//         $y += 4;
//     }
// }

// /**
//  * THIS FUNCTION NO LONGER GETS CALLED. The Transmitted As section has been removed
//  * Adds Transmitted As items to the Transmitted As section before Remarks
//  * @param $x    - start x pos
//  * @param $y    - start y pos
//  * @param $arr  - array for AS TRANSMITTED list
//  * @param $doc  - main pdf doc
//  */
// function addCheckBoxes($x, $y, $arr, $doc){
//     $doc->SetFont('courier', '', 11);
//     //breaks into 2 cols of 7 items
//     //$doc->Text($x, $y, $arr[0]);
//     $ypos = $y;
//     $len = sizeof($arr);
//     for($i=0; $i<7; $i++){
//         if(!$arr[$i]){
//             break;
//         }
//         $doc->Text($x, $ypos, "-");
//         $doc->Text($x+3, $ypos, $arr[$i]);
//         $ypos += 4;;
//     }
//     $ypos = $y;
//     $x += 80;
//     for($i=7; $i<$len; $i++){
//         if(!$arr[$i]){
//             break;
//         }
//         $doc->Text($x, $ypos, "-");
//         $doc->Text($x+3, $ypos, $arr[$i]);
//         $ypos += 4;;
//     }
// }

/***********************************************************************************************************************
 * Start PDF creation
 */

// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// // set document information
// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('Dickerson Engineering, Inc.');
// $pdf->SetTitle('DEI Letter of Transmittal For Job # '.$jobNumber);
// $pdf->SetSubject('Dickerson Engineering Letter of Transmittal');
// $pdf->SetKeywords('Dickerson, Engineering, transmittal');

// // remove default header/footer
// $pdf->setPrintHeader(false);
// $pdf->setPrintFooter(false);

// // set default monospaced font
// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// // set page orientation
// $pdf->SetPageOrientation('P');

// //set margins
// $pdf->SetMargins(0.25,0.25,0.25);

// //set auto page breaks
// $pdf->SetAutoPageBreak(TRUE, 0.25);

// //set image scale factor
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// //set some language-dependent strings
// //$pdf->setLanguageArray($l);

// $pdf->SetFont('helvetica', 'B', 16); // set font

// $pdf->AddPage("P","LETTER"); // add a page


// $pdf->SetLineWidth(0.6); // set line width to 0.6mm
// $pdf->Line(11,7,201,7); // draw a line
// $pdf->Line(11,47,201,47);
// $pdf->Line(11,80,201,80);

// $pdf->SetLineWidth(0.25); // set line width to 0.25mm

// $pdf->Line(126,26,126,47);
// $pdf->Line(201,26,201,47);
// $pdf->Line(126,26,201,26);
// $pdf->Line(126,33,201,33);
// $pdf->Line(126,40,201,40);
// //for copies/description table
// $rectstyle = array('L' => 0,'T' => 0,'R' => 0, 'B' => 0);
// $pdf->Rect(15,116,182,6, 'F', $rectstyle, array(220, 220, 220));
// $pdf->Line(15,122,197,122);
// $pdf->Line(15,116,197,116);
// $pdf->Line(15,180,197,180);
// $pdf->Line(15,116,15,180);
// $pdf->Line(197,116,197,180);
// $pdf->Line(30,116,30,180);
// $pdf->Line(55,116,55,180);
// $pdf->Line(70,116,70,180);
// $pdf->SetFont('helvetica', 'B', 8);
// $pdf->Text(17,117, "Copies");
// $pdf->Text(38, 117, "Date");
// $pdf->Text(56, 117, "Number");
// $pdf->Text(120,117,"Description");
// //end description table
// //for remarks box
// $pdf->Line(15,185,197,185);
// $pdf->Line(15,250,197,250);
// $pdf->Line(15,185,15,250);
// $pdf->Line(197,185,197,250);
// $pdf->SetFont('helvetica', 'B', 10);
// $pdf->Text(16,186, "Remarks:");
// //    end remarks box

// $pdf->SetXY(135,20); // position for image
// $pdf->Image("../deilogo.jpg",93,9,22); // display image NOTE: 'deilogo.jpg' must be copid into same folder as script

// $pdf->SetFont('helvetica', 'B', 16); // set font
// $pdf->Text(10,14,"Dickerson Engineering, Inc."); // write company name
// $pdf->Text(125,14,"Letter of Transmittal"); // write text

// $pdf->SetFont('helvetica', '', 12); // set font
// $pdf->Text(10,21,"3343 North Ridge Avenue"); // write address text
// $pdf->Text(10,26,"Arlington Heights, Illinois 60004");

// $pdf->SetFont('helvetica', '', 10); // set font
// $pdf->Text(10,36,"(847) 966-0290 Fax: (847) 966-0294"); // write text
// $pdf->Text(128,28,"DATE:");
// $pdf->Text(128,35,"DEI #:");
// $pdf->Text(128,42,"Client #:");

// $pdf->SetFont('helvetica', 'B', 12); // set font
// $pdf->Text(125,49,"Project:"); // write text


// //JOB/PROJECT AND COMPANY INFO (header)
// $pdf->SetFont("helvetica", "", 12);  // font for all main text

// $pdf->Text(145,28,$date->format("Y-m-d"));
// $pdf->Text(145,35,$jobNumber);
// $pdf->Text(145,42,$clientNumber);

// $pdf->Text(13,49,$company);
// $pdf->Text(13,53,$addr1);
// if (strlen($addr2) > 0) {
//     $pdf->Text(13,57,$addr2);
//     $pdf->Text(13,61,$city.", ".$state."  ".$zip);
//     $pdf->Text(13,65, "ATTN: ");
//     $pdf->Text(27,65, $attention);
// }
// else{
//     $pdf->Text(13,57,$city.", ".$state."  ".$zip);
//     $pdf->Text(13,61, "ATTN: ");
//     $pdf->Text(27,61, $attention);
// }

// //NOTE: project description string is confined by multicell dimensions, may have to split by line
// $pdf->MultiCell(70, 5, $project, 0, 'L', 0, 1, 125, 56,true );
// //END JOB/PROJECT AND COMPANY INFO

// //MAIL OPTIONS (attached/under separate cover VIA _________ the following items:)
// $pdf->SetFont("helvetica", 'B', 11);
// $pdf->Text(13, 82, "WE ARE SENDING YOU ");
// $pdf->Text(115, 82, "Via");
// $pdf->Text(160, 82, "the following items:");
// $pdf->SetFont('helvetica', '', 11);
// $pdf->Text(60, 82, $rBtn);
// $pdf->Text(123, 82, $via);
// addMailCheckboxes($items, $pdf);
// //END MAIL OPTIONS

// //add items to table
// addToTable($copies, $dates, $numbers, $descriptions, $pdf);

// //add remarks text
// $pdf->SetCellPaddings(1,1,1,1);
// $pdf->SetCellmargins(1,1,1,1);
// $pdf->SetFont('helvetica', '', 10);
// $pdf->MultiCell(120, 10, $remarks, 0, 'L', 0, 1, 20, 190,true );

// //add fine print and signature field
// $pdf->SetFont('helvetica', 'B', 11);
// $pdf->Line(146,260,197,260);
// $pdf->Text(126,253, "SIGNED: ");
// $pdf->SetFont('helvetica', '', 11);
// $pdf->Text(149, 253, $signed);


// //local root changes when in the server :o
// $localRoot = '/Transmittal.pdf';

// $pdf->Output($localRoot, 'F');

// $printerPathA = '\\\\Server2008\\AdminPrinter\Transmittal.pdf';
// $printerPath = '\\\\Server2008\\@HP6015x\Transmittal.pdf';         // default
// $printerPathR = '\\\\Server2008\\@RickPrinter\Transmittal.pdf';    //Rick's printer
// $printerPathJ = '\\\\Server2008\\@hp3800\Transmittal.pdf';         //Jay's printer


// if($debug){
//     // copy($localRoot, $printerPathA);
//     echo "no print because debug :)";
// }
// else {
//     for ($i = 0; $i < $dupl; $i++) {            //copy outputed pdf to printer for however many duplicates we need
//         if ($isJay) copy($localRoot, $printerPathJ);
//         else if ($isRick) copy($localRoot, $printerPathR);
//         else if ($isAdmin) copy($localRoot, $printerPathA);
//         else copy($localRoot, $printerPath);
//     }
//     echo "$dupl transmittal form(s) sent to printer<br>";
// }

$mainPdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
transmittalPDF($mainPdf);
makeLblEnv($printLblMain, $printEnvMain);

//now check for extras
for($i = 0; $i<2; $i++){
    if($extraCode[$i]){
        if($debug){
            echo "extraCode".$i."<br>";
        }
        $_GET['value'] = $extraCode[$i];
        $_GET['flag'] = "clientCode";
        $_GET['ret'] = 1;
        $extraInfo = include("../assets/php/fillAddress.php");

        $company = $extraInfo['company'];
        $addr1 = $extraInfo['addr1'];
        $addr2 = $extraInfo['addr2'];
        $city = $extraInfo['city'];
        $state = $extraInfo['state'];
        $zip = $extraInfo['zip'];
        $attention = $extraName[$i];

        if(in_array("env".($i+1), $copyEnv)){
            $printEnvMain = true;
        }
        if(in_array("lbl".($i+1), $copyLbl)){
            $printLblMain = true;
        }
        
        $copyPdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        transmittalPDF($copyPdf);
        makeLblEnv($printLblMain, $printEnvMain);
    }
}



// $q = [];
// $q[] = $jobNumber;
// $q[] = $attention;
// $q[] = $company;
// $q[] = $addr1;
// if($addr2) $q[] = $addr2;
// $q[] = $city;
// $q[] = $state;
// $q[] = $zip;

// $_GET['q'] = json_encode($q);           //print envelope and print label use the same array structure and q as parameter
// if($printLblMain){
//     include("printLabel.php");
//     echo "Address info sent to label printer<br>";
// }
// if($printEnvMain){
//     include("printEnvelope.php");
//     echo "Address info sent to envelope printer<br>";
// }

