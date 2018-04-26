<?php

// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);
// ini_set('max_execution_time', 900);
include "../UPHead.php";
require_once "UPClass2.php";

$id = $_GET['ID'];
$grd = $_GET['GRD8'];
$chgHrs = $_GET['chgHrs'];
$action = $_GET['Action'];

$srow = UPClass2::getSchedByProdid($con, $id);

if (trim($action) == 'DD') {
    UPClass2::UpdateProdSchedDD ($con, $id, $chgHrs ); 
}

if (trim($action) == 'CT') {
    // get the current end time
    $chgHrs = $_GET['chgHrs'];
    $eHours = $srow['ESTHOURS'];
  //  var_dump($chgHrs, $eHours);
    $estHours = $eHours + $chgHrs;
    UPClass2::UpdateProdSchedCT ($con, $id, $chgHrs, $estHours); 

}

//var_dump($s, db2_stmt_errormsg());
$inclwip = false;
//  UPClass::resetProdSched($con, $id, $inclwip);
$rtn = UPClass2::setSchedule($con);
?>
 "<script>
     window.close();
     window.opener.location.reload();
     </script>";
