<?php
require_once '../uphead.php';

$row=array();
$label = array();
$data1 = array();
$data2=array();
$cdata = array();
$ci=0;
$colors[1] = '#133CAC';
$colors[2] = '#2B4281';
$colors[3] = '#062270';
$colors[4] = '#476DD5';
$colors[5] = '#6D89D5';
$colors[6] = '#3C13AF';
$colors[7] = '#432C83';
$colors[8] = '#230672';
$colors[9] = '#6D48D7';
$colors[10] = '#8A6ED7';
$colors[11] = '#028E6B';
$colors[12] = '#1E6D74';
$colors[13] = '#015C65';
$colors[14] = '#35C0CD';
$colors[15] = '#5EC4CD';
$colors[16] = '#FFAD00';
$colors[17] = '#B93130';
$colors[18] = '#A67000';
$colors[19] = '#FFC140';
$colors[20] = '#FFD273';

$s = "select bw,  f_MBYRID(substr(eventname,1,1), year(entryon)) as MID, 
  sum(cast(value as dec (10,0))) / count(*)  AS FPM                
 from prod_reelBW                                                  
where year(ENTRYON) = year(current date) AND BW IS NOT NULL        
    AND f_MBYRID(substr(eventname,1,1), year(entryon)) IS NOT NULL 
group by bw, substr(eventname,1,1), year(entryon) ,                
   f_MBYRID(substr(eventname,1,1), year(entryon))                  
order by bw    , substr(eventname,1,1)                                                     ";


$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
$data = array();
$data1 = array();
$savbw = '';
while($row= db2_fetch_assoc($r)){
    
 $bw = $row['BW'];
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
  $data[]= $x; 
  $data1[] = $fpm;
    
    
}





$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;
$jTableResult['Labels'] = $label;
 $jTableResult['Data2'] = $cdata;
$jTableResult['Data1'] = $data1;
//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);
