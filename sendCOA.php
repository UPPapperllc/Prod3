<?php
require '../uphead.php';
$ord = $_GET['O'];
$shp = $_GET['S'];

$s = "call COA2('$ord', '$shp')";
$r = db2_exec($con, $s);
var_dump($s, db2_stmt_errormsg());
?>