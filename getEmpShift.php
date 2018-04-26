<?php
require_once '../uphead.php';

$action = $_GET['action'];

if (trim($action) == 'list'){

$data = array();
$s = "select * from EmpSchedule ";
$r = db2_exec($con, $s);
while($row = db2_fetch_assoc($r)){
    $data[] = $row;
}




$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;

//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);

}

if (trim($action) == 'update'){
    
    $f1 = $_POST['SUPERVISOR'];
    $f2 = $_POST['MACHTENDER'];
    $f3 = $_POST['BACKTENDER'];
    $f4 = $_POST['THIRDHAND'];
    $f5 = $_POST['FOURTHHAND'];
    $f6 = $_POST['FIFTHHAND'];
    $f7 = $_POST['LEFTHAND'];
    $f8 = $_POST['RIGHTHAND'];
    $f9 = $_POST['LAB'];
    $f10 = $_POST['PULPOPERATOR'];
    $f11 = $_POST['MATERIALHANDLER1'];
    $f12 = $_POST['MATERIALHANDLER1'];
    $f13 = $_POST['LOADER1'];
    $f14 = $_POST['LOADER2'];   
    $f15 = $_POST['ROLLFINISHER'];
    $f16 = $_POST['WTPOPERATOR'];
    $f17 = $_POST['BOILEROPERATOR'];
    $f18 = $_POST['OPERATIONSRELIEF'];
    $f19 = $_POST['SHIFTID'];
 //   $f20 = $_POST['SHIFTNAME'];
 //  $f21 = $_POST['SHIFTIMG'];
    $f22 = $_POST['REPORTEMAIL'];
    
    
    $s = "Update EmpSchedule set
SUPERVISOR     = '$f1',
MACHTENDER      = '$f2',
BACKTENDER      = '$f3',
THIRDHAND       = '$f4',
FOURTHHAND      = '$f5',
FIFTHHAND       = '$f6',
LEFTHAND        = '$f7',
RIGHTHAND       = '$f8',
LAB             = '$f9',
PULPOPERATOR    = '$f10',
MATERIALHANDLER1  = '$f11',
MATERIALHANDLER2  = '$f12',
LOADER1          = '$f13',
LOADER2    = '$f14',
ROLLFINISHER    = '$f15',
WTPOPERATOR     = '$f16',
BOILEROPERATOR  = '$f17',
OPERATIONSRELIEF = '$f18',
REPORTEMAIL          = '$f22'

Where Shiftid = '$f19' WITH NC";
    $r = db2_exec($con, $s);
  //  var_dump($s, db2_stmt_errormsg());
    $s = "Select * from EmpSchedule where SHIFTID = '$f19'";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data = array();
    
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data;
    
    //$jTableResult['TotalRecordCount'] = $row2['CNT'];
    print json_encode($jTableResult);
    
}