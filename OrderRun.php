<?php
require_once '../uphead.php';
$year = date('y');
$year3 = (int)$year - 1;
$year4 = (int)$year - 2;
$ord = $_GET['O'];
$shp = $_GET['S'];

$s = "select distinct milrn# as MR                                
 from milposp  where milord = '$ord' and ship# = $shp  ";

$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
$data = array();
while($row = db2_fetch_assoc($r)){
    $x['MILRUN'] = $row['MR'];
    $mr = $row['MR'];
    $s2 = "select * from prod_schedule where milrun =  $mr"; 
    $r2 = db2_exec($con, $s2);
    $cnt = 0;
    while ($row2 = db2_fetch_assoc($r2)){
        $cnt += 1;
        $x['SCHDT'] = $row2['SCHDATE'] . '/' . $row2['SCHTIME'];
        $x['STATUS'] = $row2['SCHEDSTAT'];
        $x['HOURS'] = $row2['ESTHOURS'];
        $data[] = $x;
    }
    if ($cnt == 0){
        $x['SCHDT'] = 'Not Scheduled';
        $x['STATUS'] = 'N/A';
        $x['HOURS'] = 0;
    $data[] = $x;
    }
    }
    
    // MILRUN, SCHDT, STATUS, HOURS
    
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data;
    
    //$jTableResult['TotalRecordCount'] = $row2['CNT'];
    print json_encode($jTableResult);
    