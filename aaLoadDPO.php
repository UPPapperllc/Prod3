<?php


require_once '../uphead.php';
$userx = 'JRMATZ';
$s = "select count(*) as CNT from dailyprodopt f1
 join dailyprod f2 on f1.pgmid = f2.pgmid and UsrID = '$userx' ";
$r = db2_exec($con, $s);
$row = db2_fetch_assoc($r);
if ($row['CNT'] == '0'){
    $s = "Select * from DailyProd ";
    $r = db2_exec($con, $s);
    while ($row = db2_fetch_assoc($r)){
        $pid = (float)$row['PGMID'];
        $s2 = "Insert into DailyProdOpt values(
        $pid,
        '$userx',
        'N'
        ) with NC";
        db2_exec($con, $s2);
             var_dump($s2, db2_stmt_errormsg());
        
    }
}