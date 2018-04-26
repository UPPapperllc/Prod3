<?php
include "../../UPHead.php";
$data = array();
//var_dump($_FILES);
$shift = trim($_GET['SHIFT']);
$sn = trim($_GET['SN'] );
$data['Shift'] = $shift;
$data['ShiftName'] = $sn;
$filename = $_FILES['SHIFTICON']['name'];
$data['FileName'] = $filename;
if (trim($filename)<> ''){
$newname = dirname(__FILE__) . '/Image/' . basename($_FILES['SHIFTICON']['name']);

$tempfile = $_FILES['SHIFTICON']['tmp_name'];

// var_dump($_FILES);
$filesize = $_FILES['infile']['size'];

$x = move_uploaded_file(trim($tempfile), trim($newname));

$s = "Update Fmanstq.EmpSchedule set SHIFTIMG = '$filename' where SHIFTID = '$shift' with NC";
$r = db2_exec($con, $s);
if (!$r){
    $data['S1'] = $s;
    $data['S1MSG'] = db2_stmt_errormsg();
    $data['failure'] = true;
} else{
    $data['Success'] = true;
}



}

