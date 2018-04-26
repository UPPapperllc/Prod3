<?php

$path = '../PHPOffice/';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require '../PHPOffice/vendor/autoload.php';

include "../UPHead.php";


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
include "../phpSpreadSheetInc.php";



use PHPOffice\PhpSpreadsheet\IOFactory;
use PHPOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing ; // Instead PHPExcel_Worksheet_Drawing
use PhpOffice\PhpSpreadsheet\Style\Alignment ; // Instead PHPExcel_Style_Alignment
use PhpOffice\PhpSpreadsheet\Style\Fill; // Instead PHPExcel_Style_Fill
use PhpOffice\PhpSpreadsheet\Style\Color; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Style\Border;


/////////////////////////////////////////////////////////////////////////////////////////////////////////////

$helper = new Sample();
$helper->log('Create new Spreadsheet object');
$spreadsheet = new Spreadsheet();

// Set document properties
$helper->log('Set document properties');
$spreadsheet->getProperties()
->setCreator("Production Schedule")
->setTitle('Production Schedule')
->setSubject("Production Schedule")
->setDescription("Production Schedule")
->setKeywords("Production Schedule excel");

$helper->log('Add some data');
//   $spreadsheet->setActiveSheetIndex(1);
//
$helper->log('Set orientation to landscape');
 //   $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

$tile = "Production Schedule " . date("M/d/Y h:i:s") ;
$spreadsheet->getActiveSheet()->setCellValue("A1", "$title");

    $spreadsheet->getActiveSheet()->setCellValue("A3", "Revision");
    $spreadsheet->getActiveSheet()->setCellValue("B3", "Run");
    $spreadsheet->getActiveSheet()->setCellValue("C3", "Grade");
    $spreadsheet->getActiveSheet()->setCellValue("D3", "Info");
$spreadsheet->getActiveSheet()->setCellValue("E3", "Notes");
$spreadsheet->getActiveSheet()->setCellValue("F3", "Est Hours");
$spreadsheet->getActiveSheet()->setCellValue("G3", "Date");
$spreadsheet->getActiveSheet()->setCellValue("H3", "Start Time");
$spreadsheet->getActiveSheet()->setCellValue("I3", "ID");

$spreadsheet->getActiveSheet()->setCellValue("L3", "Status");
$spreadsheet->getActiveSheet()->setCellValue("M3", "Required By Date");
$spreadsheet->getActiveSheet()->setCellValue("N3", "Priority");


$line = 4;

$s = "Select * from PROD_SCHEDULE where SCHEDSTAT not in('complete', 'Closed','closed','Complete')  order by SCHTS"	;
$r = db2_exec($con, $s);

$c=0;

//var_dump($con, $r, $s, db2_stmt_errormsg());
if (!$r) {
    var_dump($r, $s, db2_stmt_errormsg());
}

while ($row = db2_fetch_assoc($r)){
    $grd = $row['GRD8'];
    $spreadsheet->getActiveSheet()->setCellValue("A$line", $row['REV']);
    $spreadsheet->getActiveSheet()->setCellValue("B$line", $row['MILRUN']);
    $spreadsheet->getActiveSheet()->setCellValue("C$line", "$grd");
    $spreadsheet->getActiveSheet()->setCellValue("D$line", $row['ADLINFO']);
    $spreadsheet->getActiveSheet()->setCellValue("E$line", $row['ADLNOTES']);
    $spreadsheet->getActiveSheet()->setCellValue("F$line", $row['ESTHOURS']);
    $spreadsheet->getActiveSheet()->setCellValue("G$line", $row['SCHDATE']);
    $spreadsheet->getActiveSheet()->setCellValue("H$line", $row['SCHTIME']);
    $spreadsheet->getActiveSheet()->setCellValue("I$line", $row['PROD_ID']);
  
    $spreadsheet->getActiveSheet()->setCellValue("L$line", $row['SCHEDSTAT']);
    $spreadsheet->getActiveSheet()->setCellValue("M$line", $row['RUNSTAT']);
    $spreadsheet->getActiveSheet()->setCellValue("N$line", $row['SHIFT']);
    
    $line +=1;
}
$lastline = $line = 1;
$alllines1 = array(
    'borders' => array(
        'allBorders' => array(
            'borderStyle' =>  PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        )
    )
);


$spreadsheet->getActiveSheet()->getStyle("A1:M$lastline")
->applyFromArray($alllines1);
$spreadsheet->getActiveSheet()
->getColumnDimension("A")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("B")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("C")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("D")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("E")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("F")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("G")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("H")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("I")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("J")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("K")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("L")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("M")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getColumnDimension("N")
->setAutoSize(true);
$spreadsheet->getActiveSheet()
->getStyle("A1:N3")
->getFont()
->setBold(true);
$spreadsheet->getActiveSheet()
->getStyle("E3:E$lastline")
->getAlignment()
->setWrapText(true);
$spreadsheet->getActiveSheet()->mergeCells("A1:N1");

$i=0;
$spreadsheet->getActiveSheet();
$filepath = "/www/zendphp7/htdocs/Prod_Schedules/";
$filename = "Prod_Schedule-" . date("Y-m-d") . ".xlsx";
$fullFileName = $filepath . $filename;
$filename2 = "Prod_Schedule-" . date("Y-m-d") . ".pdf";
$i=0;
//$spreadsheet->getActiveSheet();
try {

$helper->write($spreadsheet,$fullFileName);
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save($fullFileName);
} catch (Exception $e) {
    echo "<br> file $filename not saved ";
    var_dump($e);
    $data['success'] = true;
    $datax['WRITE_ERROR'] = $e;
}


?>
<script>
    var url = 'PSXLS.php' ;
			            window.open(src=url, 'psxls', "width=300,height=50");
window.close();</script>