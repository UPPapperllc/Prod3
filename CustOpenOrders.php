<?php

require_once '../uphead.php';


    $cust = $_GET['CUST'];
    $s = "select F1.SCODE, f1.MILORD, SHIP#, RYEAR, RMONTH, RDAY, ROLWTH, ROLFRC, f1.CARVIA from ordatep f1 left join ordentp f2 on f2.milord =    
f1.milord where custsh = $cust and f1.scode not in ('I', 'X', 'C')  ";
    $r = db2_exec($con, $s);

    
    
    
    
    
    $data = array();
    $id = 1;
    while($row = db2_fetch_assoc($r)){
        
        $x['SCODE'] = $row['SCODE'];
        $x['MILORD'] = $row['MILORD'];
        $x['SHIP'] = $row['SHIP#'];
        $x['REQDATE'] = '20'.$row['RYEAR'] . '-' . $row['RMONTH'] . '-' . $row['RDAY'];
        $x['ROLLWIDTH'] = $row['ROLWTH'] . ' ' . $row['ROLFRC'];
        $x['CARVIA'] = $row['CARVIA'];
        $data[] = $x;
    }
    
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data;
    
    //$jTableResult['TotalRecordCount'] = $row2['CNT'];
    print json_encode($jTableResult);