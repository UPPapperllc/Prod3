<?php
require_once '../uphead.php';

$bw = $_GET['BW'];
$mid = $_GET['MID'];

$row=array();
$label = array();
$data1 = array();
$data2=array();
$cdata = array();
$ci=0;
$colors[1] = '#ffccc';
$colors[2] = '#ccddff';
$colors[3] = '#ccffcc';
$colors[4] = '#ffffcc';
$colors[5] = '#fff2cc';
$colors[6] = '#ffccff';
$colors[7] = '#d6d6f5';
$colors[8] = '#ccfff2';
$colors[9] = '#ff6666';
$colors[10] = '#ff66d9';
$colors[11] = '#668cff';
$colors[12] = '#66ff66';
$colors[13] = '#ffff66';
$colors[14] = '#ff3333';
$colors[15] = '#cc33ff';
$colors[16] = '#3399ff';
$colors[17] = '#33ff77';
$colors[18] = '#ff884d';
$colors[19] = '#ff0066';
$colors[20] = '#ffff00';

$s = "select PROD_REELBW.*,                                             
 f_MBYRID(substr(eventname,1,1), year(entryon)) as MID,           
f_lenbybw(BW, substr(eventname,1,1), year(current date)) as FPM   
 from prod_reelBW                                                 
where year(ENTRYON) = year(current date) AND BW IS NOT NULL       
    AND f_MBYRID(substr(eventname,1,1), year(entryon)) IS NOT NULL
and BW = $bw           and f_MBYRID(substr(eventname,1,1), year(entryon)) = '$mid'                                            
order by entryon, bw                                               ";


$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
$data = array();
$data1 = array();
$savbw = '';
while($row= db2_fetch_assoc($r)){
    
 
 $mid = $row['MID'];
 $fpm = $row['FPM'];
 $x['BW'] = $bw;
 $x['MID'] = $mid;
 $x['FPM'] = $fpm;
 
 $y = array();
 
 $y[] = $mid;
 if($bw !== $savbw){
   $ci+=1;
 $y[] = $bw;
 $savbw = $bw;
 }
 $cdata[] = $colors[$ci];
 $label[] = $y;
  $data[]= $row; 
  $data1[] = $fpm;
    
    
}





$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;
//$jTableResult['Labels'] = $label;
// $jTableResult['Data2'] = $cdata;
//$jTableResult['Data1'] = $data1;
//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);
