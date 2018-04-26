<?php
require '../uphead.php';
$ord = $_GET['O'];
$shp = $_GET['S'];

$s = "Update ORDATEP set Scode = 'X' where MILORD = '$ord' and ship# = $shp with NC";
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
?>

<script>
window.close();
window.opener.location.reload(true);
</script>