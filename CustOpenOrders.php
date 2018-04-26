<?php

require_once '../uphead.php';


if (isset($_GET['jtStartIndex']))  $start = $_GET['jtStartIndex']; else $start = 1;
if (isset($_GET['jtPageSize']))  $pagesize = $_GET['jtPageSize'];else $pagesize = 15;

if (isset($_GET['jtSorting'])){
  $orderby = 'Order by ' . $_GET['jtSorting'];  
} else $orderby = "Order by MILORD ";

    $cust = $_GET['CUST'];
    
    $s = "select count(*) as CNT from ordatep f1 left join ordentp f2 on f2.milord =    
f1.milord where custsh = $cust and f1.scode not in ('I', 'X', 'C') ";
    $r = db2_exec($con, $s);
    $rowcnt = db2_fetch_assoc($r);
    $s = "select F1.SCODE, f1.MILORD, SHIP#, RYEAR, RMONTH, RDAY, ROLWTH, ROLFRC, f1.CARVIA from ordatep f1 left join ordentp f2 on f2.milord =    
f1.milord where custsh = $cust and f1.scode not in ('I', 'X', 'C')  $orderby ";
    $r = db2_exec($con, $s,array('cursor' => DB2_SCROLLABLE));

    $i = $start    + $pagesize;
    if ($start == 0) $start = 1;
    
    
    
    
    $data = array();
    $id = 1;
    while($row = db2_fetch_assoc($r, $start) and $start< $i ) {
        
        $x['SCODE'] = $row['SCODE'];
        $x['MILORD'] = $row['MILORD'];
        $x['SHIP'] = $row['SHIP#'];
        $x['REQDATE'] = '20'.$row['RYEAR'] . '-' . $row['RMONTH'] . '-' . $row['RDAY'];
        $x['ROLLWIDTH'] = $row['ROLWTH'] . ' ' . $row['ROLFRC'];
        $x['CARVIA'] = $row['CARVIA'];
        $data[] = $x;
        $start++;
    }
    
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rowcnt['CNT'];
    $jTableResult['Records'] = $data;
    
    //$jTableResult['TotalRecordCount'] = $row2['CNT'];
    print json_encode($jTableResult);