<?php
/**********************************************************************************************************************
 * This script handles pdf creation and printing, included in all 'print.php' files for calling _____PDF functions 
 * for each type of pdf.
 */

require_once('C:\\Webroot\\tcpdf6\\tcpdf.php');     //require tcpdf library
require_once('C:\\Webroot\\tcpdf6/examples/lang/eng.php');

/***********************************************************************************************************************
 * Set up printer global
 */
$printerPath = "\\\\Server2008\\@HP6015x";
if($isJay) $printerPath = "\\\\Server2008\\@hp3800";
if($isRick) $printerPath = "\\\\Server2008\\@RickPrinter";
if($isAdmin) $printerPath = "\\\\Server2008\\AdminPrinter";

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

//this function iterates through each of the field arrays and pastes them into the pdf table
function addTotals($starty, $doc, $set, $sheet, $size, $color, $cost, $total){
    $doc->SetFont('courier', '', 10);

    $y = $starty;
    for($i = 0; $i<4; $i++){
        if($set[$i]){
            $doc->Text(24, $y, $set[$i]);
            $doc->Text(50, $y, $sheet[$i]);
            $doc->Text(72, $y, $size[$i]);
            $doc->Text(101, $y, "Paper");
            $doc->Text(132, $y, $color[$i]);
            $doc->Text(154, $y, $cost[$i]);
            $doc->Text(180, $y, $total[$i]);
            $y += 6;
        }
    }
}

//function to initialize PDF settings (title, subject, header, etc.)
function initializePDF($pdf, $title, $jobNumber){
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Dickerson Engineering, Inc.');
    $pdf->SetTitle('DEI '.$title.' For Job # '.$jobNumber);
    $pdf->SetSubject('Dickerson Engineering '.$title);
    $pdf->SetKeywords('Dickerson, Engineering, '.$title.', '.$jobNumber);

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set page orientation
    $pdf->SetPageOrientation('P');

    //set margins
    $pdf->SetMargins(0.25,0.25,0.25);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 0.25);

    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //set some language-dependent strings
    // $pdf->setLanguageArray($l);

    $pdf->SetFont('helvetica', 'B', 16); // set font

    $pdf->AddPage("P","LETTER"); // add a page

    $pdf->SetXY(135,20); // position for image
    $pdf->Image("../deilogo.jpg",93,9,22); // display image NOTE: 'deilogo.jpg' must be copid into same folder as script

    $pdf->SetLineWidth(0.6); // set line width to 0.6mm
    $pdf->Line(11,7,201,7); // draw a line
    $pdf->Line(11,47,201,47);

    $pdf->SetFont('helvetica', 'B', 16); // set font
    $pdf->Text(10,14,"Dickerson Engineering, Inc."); // write company name
    $pdf->SetFont('helvetica', '', 12); // set font
    $pdf->Text(10,21,"3343 North Ridge Avenue"); // write address text
    $pdf->Text(10,26,"Arlington Heights, Illinois 60004");
    $pdf->SetFont('helvetica', '', 10); // set font
    $pdf->Text(10,36,"(847) 966-0290 Fax: (847) 966-0294"); // write text
}

/***********************************************************************************************************************
 * Start PDF creation
 */

// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

function transmittalPDF(){
    global $jobNumber, $date, $clientNumber, $company, $addr1, $addr2, $city, $state, $zip, $attention;
    global $project, $rBtn, $via, $items, $copies, $dates, $numbers, $descriptions, $remarks, $signed, $dupl;
    global $debug, $printerPath;

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    initializePDF($pdf, "Letter of Transmittal", $jobNumber);

    $pdf->SetLineWidth(0.6); // set line width to 0.6mm
    $pdf->Line(11,80,201,80);

    $pdf->SetLineWidth(0.25); // set line width to 0.25mm

    $pdf->Line(126,26,126,47);
    $pdf->Line(201,26,201,47);
    $pdf->Line(126,26,201,26);
    $pdf->Line(126,33,201,33);
    $pdf->Line(126,40,201,40);

    //for copies/description table
    $rectstyle = array('L' => 0,'T' => 0,'R' => 0, 'B' => 0);
    $pdf->Rect(15,116,182,6, 'F', $rectstyle, array(220, 220, 220));
    $pdf->Line(15,122,197,122);
    $pdf->Line(15,116,197,116);
    $pdf->Line(15,180,197,180);
    $pdf->Line(15,116,15,180);
    $pdf->Line(197,116,197,180);
    $pdf->Line(30,116,30,180);
    $pdf->Line(55,116,55,180);
    $pdf->Line(70,116,70,180);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Text(17,117, "Copies");
    $pdf->Text(38, 117, "Date");
    $pdf->Text(56, 117, "Number");
    $pdf->Text(120,117,"Description");
    //end description table
    //for remarks box
    $pdf->Line(15,185,197,185);
    $pdf->Line(15,250,197,250);
    $pdf->Line(15,185,15,250);
    $pdf->Line(197,185,197,250);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(16,186, "Remarks:");
    //    end remarks box

    $pdf->SetFont('helvetica', 'B', 16); // set font
    $pdf->Text(125,14,"Letter of Transmittal"); // write text

    $pdf->SetFont('helvetica', '', 10); // set font
    $pdf->Text(10,36,"(847) 966-0290 Fax: (847) 966-0294"); // write text
    $pdf->Text(128,28,"DATE:");
    $pdf->Text(128,35,"DEI #:");
    $pdf->Text(128,42,"Client #:");

    $pdf->SetFont('helvetica', 'B', 12); // set font
    $pdf->Text(125,49,"Project:"); // write text


    //JOB/PROJECT AND COMPANY INFO (header)
    $pdf->SetFont("helvetica", "", 12);  // font for all main text

    $pdf->Text(145,28,$date->format("Y-m-d"));
    $pdf->Text(145,35,$jobNumber);
    $pdf->Text(145,42,$clientNumber);

    $pdf->Text(13,49,$company);
    $pdf->Text(13,53,$addr1);
    if (strlen($addr2) > 0) {
        $pdf->Text(13,57,$addr2);
        $pdf->Text(13,61,$city.", ".$state."  ".$zip);
        $pdf->Text(13,65, "ATTN: ");
        $pdf->Text(27,65, $attention);
    }
    else{
        $pdf->Text(13,57,$city.", ".$state."  ".$zip);
        $pdf->Text(13,61, "ATTN: ");
        $pdf->Text(27,61, $attention);
    }

    //NOTE: project description string is confined by multicell dimensions, may have to split by line
    $pdf->MultiCell(70, 5, $project, 0, 'L', 0, 1, 125, 56,true );
    //END JOB/PROJECT AND COMPANY INFO

    //MAIL OPTIONS (attached/under separate cover VIA _________ the following items:)
    $pdf->SetFont("helvetica", 'B', 11);
    $pdf->Text(13, 82, "WE ARE SENDING YOU ");
    $pdf->Text(115, 82, "Via");
    $pdf->Text(160, 82, "the following items:");
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Text(60, 82, $rBtn);
    $pdf->Text(123, 82, $via);
    addMailCheckboxes($items, $pdf);
    //END MAIL OPTIONS

    //add items to table
    addToTable($copies, $dates, $numbers, $descriptions, $pdf);

    //add remarks text
    $pdf->SetCellPaddings(1,1,1,1);
    $pdf->SetCellmargins(1,1,1,1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(120, 10, $remarks, 0, 'L', 0, 1, 20, 190,true );

    //add fine print and signature field
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Line(146,260,197,260);
    $pdf->Text(126,253, "SIGNED: ");
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Text(149, 253, $signed);


    //local root changes when in the server :o
    $localRoot = '/Transmittal.pdf';

    $pdf->Output($localRoot, 'F');

    $mPrinter = $printerPath.$localRoot;

    if($debug){
        // copy($localRoot, $printerPathA);
        echo "DEBUG:<br>$dupl transmittal form(s) to $company sent to printer<br>";
    }
    else {
        for ($i = 0; $i < $dupl; $i++) {            //copy outputed pdf to printer for however many duplicates we need
            copy($localRoot, $mPrinter);
        }
        echo "$dupl transmittal form(s) to $company ($addr1,$city) sent to printer<br>";
    }
}

function faxPDF(){
    global $jobNumber, $date, $clientCode, $attention, $company, $fax, $from, $project, $numPages, $extraComp, $extraFax, $extraName;
    global $willFollow, $remarks, $printerPath, $debug;

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    initializePDF($pdf, "Fax Transmittal", $jobNumber);

    $pdf->SetFont('helvetica', 'B', 16); // set font
    $pdf->Text(125,14,"Fax Transmittal"); // write text

    $pdf->SetFont('helvetica', '', 10); // set font
    $pdf->Text(128,28,"DATE:");
    $pdf->Text(128,35,"DEI #:");
    //$pdf->Text(128,42,"Client #:");

    $pdf->SetFont('helvetica', '', 10); // set font
    $pdf->Text(12, 50, "To:");
    $pdf->Text(12, 58, "Of:");
    $pdf->Text(12, 66, "Project: ");
    $pdf->Text(12,82,"From:"); // write text
    $pdf->Text(12, 92, "Number of pages: ");

    $pdf->Text(12, 192, "At Fax Number: ");
    $pdf->Text(12, 200, "Copies to: ");
    $pdf->Text(140, 200, "FAX #:");
    $pdf->Text(140, 210, "FAX #:");

    $pdf->Text(46, 250, "If you do not receive the number of pages specified above, please alert DEI.");

    $pdf->SetFont('helvetica', 'b', 12);
    $pdf->Text(144,27, $date->format("m-d-Y"));
    $pdf->Text(144, 34, $jobNumber);
    $pdf->Text(30, 50, $attention);
    $pdf->Text(30, 58, $company);
    //$pdf->Text(30, 66, $project);
    $pdf->MultiCell(120,10, $project, 0, 'L', 0, 1, 30, 66, true);
    $pdf->Text(30, 82, $from);
    $pdf->Text(45, 91, $numPages);
    $pdf->Text(40, 192, $fax);

    $pdf->MultiCell(120, 10, $remarks, 0, 'L', 0, 1, 35, 106,true );

    $y = 200;
    for($i=0; $i< sizeof($extraComp); $i++){
        $pdf->Text(40, $y, $extraComp[$i]);

        $pdf->Text(152, $y, $extraFax[$i]);
        $y += 10;
    }
    if($willFollow){
        $pdf->SetFont('helvetica', 'b', 10);
        $pdf->Text(76, 240, "Transmitted document will follow");
    }

    $localRoot = '/fax.pdf';

    $mPrinter = $printerPath.$localRoot;

    $pdf->Output($localRoot, 'F');
    //echo $localRoot;
    if(!$debug){
        copy($localRoot, $mPrinter);
        echo "Fax transmittal to $company sent to printer<br>";
    }
    else{
        echo "DEBUG:<br>Fax transmittal to $company sent to printer (not)<br>";
    }
}

function billPlotPDF(){
    global $date, $jobNumber, $clientCode, $clientNumber, $attention, $authBy, $project, $invoice;
    global $company, $addr1, $addr2, $city, $state, $zip, $description;
    global $numSets, $numSheets, $sheetSizes, $colored, $costs, $lineTotals, $total;
    global $printerPath, $debug;

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    initializePDF($pdf, "Plotting Record", $jobNumber);

    $pdf->SetLineWidth(0.6); // set line width to 0.6mm
    $pdf->Line(11,80,201,80);

    $pdf->SetLineWidth(0.25); // set line width to 0.25mm
    //$pdf->Line(11,83,201,83); // draw a lines
    $pdf->Line(126,26,126,47);
    $pdf->Line(201,26,201,47);
    $pdf->Line(126,26,201,26);
    $pdf->Line(126,33,201,33);
    $pdf->Line(126,40,201,40);

    //for description box
    $pdf->Line(15,150,197,150);
    $pdf->Line(15,260,197,260);
    $pdf->Line(15,150,15,260);
    $pdf->Line(197,150,197,260);
    $pdf->Line(175,118, 197,118);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(16,151, "Description:");
    //end remarks box

    $pdf->SetFont('helvetica', 'B', 16); // set font
    $pdf->Text(125,14,"Plotting Record #"); // TODO: add bill record #
    $pdf->Text(175, 14, $invoice);

    $pdf->SetFont('helvetica', '', 10); // set font
    $pdf->Text(128,28,"DATE:");
    $pdf->Text(128,35,"DEI #:");
    $pdf->Text(128,42,"Client #:");

    $pdf->SetFont('helvetica', 'B', 12); // set font
    $pdf->Text(125,49,"Project:"); // write text


    //JOB/PROJECT AND COMPANY INFO (header)
    $pdf->SetFont("courier", "", 12);  // font for all main text

    $pdf->Text(145,28, $date->format("m-d-Y"));
    $pdf->Text(145,35, $jobNumber);
    $pdf->Text(145,42, $clientNumber);

    $pdf->Text(13,49, $company);
    $pdf->Text(13,53, $addr1);
    if($addr2){
        $pdf->Text(13,57, $addr2);
        $pdf->Text(13,61,$city.", ".$state."  ".$zip);
        $pdf->Text(13, 65, "ATTN: ".$attention);
    }
    else{
        $pdf->Text(13,57,$city.", ".$state."  ".$zip);
        $pdf->Text(13, 61, "ATTN: ".$attention);
    }

    //$pdf->Text(125,56,$project);
    $pdf->MultiCell(70, 5, $project, 0, 'L', 0, 1, 125, 56,true );

    //END JOB/PROJECT AND COMPANY INFO

    //add table headers
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Text(20, 84, "Sets");
    $pdf->Text(40, 84, "Sheets/Set");
    $pdf->Text(75, 84, "Size");
    $pdf->Text(100, 84, "Media");
    $pdf->Text(130, 84, "Color");
    $pdf->Text(150, 84, "Cost/Sheet");
    $pdf->Text(180, 84, "Total");
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Text(90, 122, "The total due for this record is");
    //end table headers

    //add table info
    addTotals(93, $pdf, $numSets, $numSheets, $sheetSizes, $colored, $costs, $lineTotals);
    $pdf->SetFont('courier', 'b', 18);
    $pdf->Text(170, 121, $total);
    //end table info

    //add description text
    $pdf->SetCellPaddings(1,1,1,1);
    $pdf->SetCellmargins(1,1,1,1);

    $pdf->SetFont('courier', '', 10);
    //$remarks = "test test test description description description wowow wowow blah blah blah blah blah i'm trying real hard to make lotso text !";
    $pdf->MultiCell(120, 10, $description, 0, 'L', 0, 1, 20, 155,true );


    $localRoot = '/bill.pdf';
    $mPrinter = $printerPath.$localRoot;

    $pdf->Output($localRoot, 'F');

    if(!$debug){
        copy($localRoot, $mPrinter);
        echo "Bill plot to $company sent to printer<br>";
    }
    else{
        echo "DEBUG:<br>Bill plot to $company sent to printer";
    }

}


