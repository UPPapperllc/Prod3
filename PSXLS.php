<?php
$filepath = "/www/zendphp7/htdocs/Prod_Schedules/";
$filename = "Prod_Schedule-" . date("Y-m-d") . ".xlsx";
$fullFileName = $filepath . $filename;
header("Content-Type: application/force-download\n");
header("Content-Disposition: attachment; filename=".$fullFileName);
readfile($fullFileName);

?>
<script>window.close();</script>