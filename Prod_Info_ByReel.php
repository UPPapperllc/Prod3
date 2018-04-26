<?php
$user = strtoupper($_SERVER['PHP_AUTH_USER']);

require '../uphead.php';
require "UPClass2.php";
$reel = $_GET['REEL'];


$s = "Select Variable, Value, Entryon, VariableID                  
from Prod_Reel where EventName = '$reel'
and year(Entryon) = Year(Current Date)                
                                        ";
$data = array();
$r = db2_exec($con, $s);
While($row = db2_fetch_assoc($r)){
    $data[] = $row;
}

$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;
//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);