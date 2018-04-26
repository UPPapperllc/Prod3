<?php
require_once '../uphead.php';

$s = "Select max(Date(SCHETS)) as Max from Prod_Schedule";
$r=db2_exec($con, $s);
$row = db2_fetch_assoc($r);

echo $row['MAX'];