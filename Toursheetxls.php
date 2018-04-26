<?php
$inipath = php_ini_loaded_file();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$path = '/PHPOffice/';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require '../PHPOffice/vendor/autoload.php';
$_GET['DB'] = 'PR';
$basePath = '/www/Zendphp7/HTDocs/';



include "../UPHead.php";
$inputFileType = 'Xlsx';
$helper = new Sample();
$template = 'TourSheet_Template.xls';
$inputFileName = "/HarrisData/EIP/Intranet/Toursheet/$template";
$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory with a defined reader type of ' . $inputFileType);
$reader = IOFactory::createReader($inputFileType);

$spreadsheet = $reader->load($inputFileName);

$indate = substr($_GET['StartDate'],0,10);
$indateb = strtotime($indate);
$indatec = date('Y-m-d', $indateb);
$thrudate = strtotime($indate);
$td = date('Y-m-d',$thrudate);                          
$dts = $_GET['DTS'];
$email = $_GET['EMAIL'];
//if (isset($_GET['Shift'])){
    $inshift = $_GET['Shift'];
    if (trim($inshift) == 'Day'){
        $stime  = '06.00.00';
        $etime = '17.59.59';
        $fdate = date('Y-m-d',strtotime($indate));
        $tdate = $indate;
    } else {
        $inshift = 'Nite';
        $stime  = '18.00.00';
        $etime = '05.59.59';
        $thrudate1 = strtotime($indate);
        $tdate = date('Y-m-d', strtotime("+1 day", $thrudate1));
        $fdate = date('Y-m-d',strtotime($indate));
    }
    
    
//}else{
  //  $ctime = date('H:i:s');
  //  if ($ctime >= '06:00:00' and $ctime < '18:00:00'){
  //      $inshift = 'Day';
  //      $stime  = '06.00.00';
  //      $etime = '17.59.59';
  //      $fdate = date('Y-m-d',strtotime($indate));
  //      $tdate = date('Y-m-d',strtotime($indate));
  //  } else {
 //       $inshift = 'Nite';
  //      $stime  = '18.00.00';
   //     $etime = '05.59.59';
    //    $thrudate1 = strtotime($indate);
     //   $tdate = date('Y-m-d', strtotime("+1 day", $thrudate1));
    //    $fdate = date('Y-m-d',strtotime($indate));
   // }
//}
$fts = $fdate . '-' . $stime . '.000000';
$tts = $tdate . '-' . $etime . '.000000';
//var_dump($indate, $td, $thrudate, $fromDate);
//$fd = date('Y-m-d',$fromDate);
//// // // echo $fd . ', ' . $td;

$data =  array();
$c = 0;
$data['success'] = 'true';
$i=0;
$line = 7;

$helper = new Sample();

$helper->log('Create new Spreadsheet object');
$spreadsheet = new Spreadsheet();

// Set document properties
$helper->log('Set document properties');
$spreadsheet->getProperties()
->setCreator("Toursheet Report")
->setLastModifiedBy("Toursheet Report")
->setTitle('Toursheet'. $indate . '/' . $shift)
->setSubject("Toursheet")
->setDescription("Toursheet")
->setKeywords("Toursheet excel");

$helper->log('Start of process');
//   $spreadsheet->setActiveSheetIndex(1);
//
$helper->log('Set orientation to landscape');
if (trim($orientation) == 'LANDSCAPE'){
    $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
} else {
    $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
    
}
//$spreadsheet->getDefaultStyle()->getFont()->setName("$font");
//$spreadsheet->getDefaultStyle()->getFont()->setSize($fontsize);


/////////////////////////////////////////////////////////////////////////





// var_dump($objPHPExcel);
////////////////////////////////////////////////////////////////////
//  Set Header values
///////////////////////////////////////////////////////////////////
if (trim($inshift) == 'Nites') $insiftxls = 'Nights' ; else $inshiftxls = $inshift;
$dateshift = 'Date/Shift: '. $inshiftxls . ' ' . $indate;
//var_dump($dateshift);

// // // echo "<br> Line 99";
//$objPHPExcel->getActiveSheet()->setTitle($dateshift);

// // // echo "<br> Line 102";
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("K1", "$dateshift"); 

// // // echo "<br> Line 105";


$s = "SELECT  * FROM fmanstq.toursheet_time f1
left join toursheet f2 on f1.sheetid = f2.sheetid
WHERE timestamp(eventdate , eventtime) >= '$fts' and
timestamp(eventdate , eventtime) <= '$tts' ORDER BY
timestamp(eventdate , eventtime) ";
$r = db2_exec($con, $s);
while ($row = db2_fetch_assoc($r)){
$sheetid = $row['SHEETID'];
$pwgt = 0;
$s2 = "SELECT TIMESTAMPDIFF(4, CHAR(timestamp(eventdate, eventtime)-
F_GETPTS(timestamp(eventdate, eventtime)))) as PT
FROM fmanstq.toursheetv2 Where SHEETID = $sheetid";
$r2 = db2_exec($con, $s2);
//   var_dump($s2, db2_stmt_errormsg());
$row2 = db2_fetch_assoc($r2);

$edur = $row2['PT'];
IF ($row['TIMEID']>= 70){
    $dtime = $edur ;
    $ptime = 0;
} else {
    $dtime = 0;
    $ptime = $edur ;
}
    
    
    if (trim($row['GRADE']) !== '' or $row['TIMEID']>= 70 ){
        $row['NETTIME'] = round(($row['PROCESSTIME'] - $row['DOWNTIME'])/60, 2);
        
        IF ($row['TIMEID']>= 70){
            $row['DOWNTIME'] = $row['EVENTDUR'] ;
            $row['PROCESSTIME'] = 0;
        } else {
            $row['DOWNTIME'] = 0;
        }
        // ---------------------------------------------------------
        $row['GROUPKEY'] = $row['SHIFTSTARTDATE'] . ' - ' .$row['SHIFT'];
        $row['EVENTTS'] = $row['EVENTDATE'] . ' ' . $row['EVENTTIME'];
       // $datax['root'][$c] = $row;
       // $c ++;
        $xlsrun = $row['MILRUN'];
        $xlsgrade = $row['GRADE'];
        $xlsbw = $row['BSWGT'];
        $xlsreelid = $row['REELID'];
        $xlsreel = $row['REEL'];
        $xlsdt = $row['DOWNTIME'];
        $xlspt = $row['PROCESSTIME'];
        $xlspw = $row['PROD_WGT'];
        $xlscmt = strip_tags($row['EVENTCOMMENT']);
       
        $objPHPExcel->setActiveSheetIndex($i)->setCellValue("A$line", "$xlsrun");
        $objPHPExcel->setActiveSheetIndex($i)->setCellValue("B$line", "$xlsgrade");
        $objPHPExcel->setActiveSheetIndex($i)->setCellValue("C$line", "$xlsbw");
        $objPHPExcel->setActiveSheetIndex($i)->setCellValue("D$line", "$xlsreelid");
        $objPHPExcel->setActiveSheetIndex($i)->setCellValue("E$line", "$xlsreel");
        $objPHPExcel->setActiveSheetIndex($i)->setCellValue("F$line", "$xlsdt");
        $objPHPExcel->setActiveSheetIndex($i)->setCellValue("G$line", "$xlspt");
        $objPHPExcel->setActiveSheetIndex($i)->setCellValue("H$line", "$xlspw");
        $objPHPExcel->setActiveSheetIndex($i)->setCellValue("I$line", "$xlscmt");
        
  //      ---------------------------------------------------------------------------------------------- */
   $line += 1;
    }
    
 
}
// // // echo "<br>Loading Team Members";
$tline = 2;
$pos = 'SUPERVISOR';
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("P$tline", "Supervisor");
$posval = getTeambyPos($con, $fts, $tts, $pos);
var_dump($posval);
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("R$tline","$posval" );
$tline += 1;

$pos = 'LAB';
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("P$tline", "lab Tec");
$posval = getTeambyPos($con, $fts, $tts, $pos);
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("R$tline","$posval" );$tline += 1;

$pos = 'MACHTENDER';
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("P$tline", "Machine Tender");
$posval = getTeambyPos($con, $fts, $tts, $pos);
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("R$tline","$posval" );$tline += 1;

$pos = 'BACKTENDER';
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("P$tline", "Back Tender");
$posval = getTeambyPos($con, $fts, $tts, $pos);
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("R$tline","$posval" );$tline += 1;

$pos = 'THIRDHAND';
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("P$tline", "3rd Hand");
$posval = getTeambyPos($con, $fts, $tts, $pos);
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("R$tline","$posval" );$tline += 1;

$pos = 'FORTHHAND';
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("P$tline", "4th Hand");
$posval = getTeambyPos($con, $fts, $tts, $pos);
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("R$tline","$posval" );$tline += 1;


$pos = 'FIFTHHAND';
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("P$tline", "5th Hand");
$posval = getTeambyPos($con, $fts, $tts, $pos);
$objPHPExcel->setActiveSheetIndex($i)->setCellValue("R$tline","$posval" );$tline += 1;



$sheetname = 'Tour_Sheet_' . trim($inshiftxls) . '_'. $indate;
$filename = "/HarrisData/EIP/IntraNet/Toursheet/$sheetname.xls";


$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
// // // echo '<br>saving the spreadsheet to IFS filename: '. $filename;

try{
    $objWriter->save($filename);
} catch (Exception $e) {
    $data['success'] = true;
    $datax['WRITE_ERROR'] = $e;
    
}
$urlb = "Http://10.6.1.11/IntraNet/Toursheet/$sheetname.xls";
$data['result'][$c] = $datax;
//echo json_encode($data);
// echo $urlb;




//echo '<br>Sending Email';
$msg = "Toursheet for $dateshift'<br>";
require 'PHPMailerAutoload.php';
$mail = new PHPMailer;

$mail->IsSMTP(); // telling the class to use SMTP

$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
// 1 = errors and messages
// 2 = messages only
$mail->SMTPAuth   = false;                  // enable SMTP authentication
$mail->Host       = "10.6.1.10"; // sets the SMTP server
$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
$mail->Username   = "WEBUSER"; // SMTP account username
$mail->Password   = "GERMANY02";        // SMTP account password






$mail->setFrom('Production_Reporting@uppaperllc.com', 'Toursheet');
//$mail->addAddress('Jim.Matz@xeratec.com');
$mail->addAddress($email);
$mail->Subject = "Toursheet for $dateshift";
$mail->AddAttachment($filename);
$mail->msgHTML($msg);
$mail->AltBody = 'Email was sent in HTML format - you will need to modify your email to allow HTML email to be received';

if (!$mail->send()) {
    //echo '<br>Email did not complete' . $mail->ErrorInfo;
    $data['failure'] = true;
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
   echo 'Your report is on-its way to Email Address ' .  $email ;
    $data['success'] = true;
}


 print json_encode($data);

 
function getTeambyPos($con, $fts, $tts, $pos){
$s = "SELECT distinct $pos as POS FROM fmanstq.toursheetv2 Where timestamp(eventdate , eventtime) >= '$fts' and timestamp(eventdate , eventtime) <= '$tts'  
and $pos <> ' '";
$r = db2_exec($con, $s);

$comma = '';
$rval = '';
while ($row = db2_fetch_assoc($r)){
    var_dump($row['POS']);
    echo '<br>';
    $rval = $comma. $row['POS'];
    $comma = ', ';
}
var_dump($rval);
echo '<br>';
return $rval;
}
