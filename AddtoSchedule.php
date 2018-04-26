<?php

require_once '../uphead.php';
require 'UPClass2.php';
// not on a production run and not produced

    $mr = $_GET['MR'];
    $s = "Select count(*) as MRCNT from MILPOSP Where milrn# = $mr";
    $r = db2_exec($con, $s);
    $rowmr = db2_fetch_assoc($r);
   // var_dump($rowmr);
    if ($rowmr['MRCNT'] !== 0) {
    $s = "Select count(*) as MRCNT from Prod_Schedule Where milrun = $mr";
    $r = db2_exec($con, $s);
    $rowmr = db2_fetch_assoc($r);
    } else $rowmr['MRCNT'] = 99;
   // var_dump($rowmr);
    if ($rowmr['MRCNT'] == 0) {
    
    
    $rev = 1;
    // get the orders but only if they exists.
    $s2 = "SELECT distinct milord, ship#, grd8 FROM milposp f1                                   
left join MILrunp f3 on f1.milrn# = f3.milrn# and f1.item# =
f3.item#                                                   
 WHERE f1.milrn# = $mr                                   
and exists (select * from ordentp f2                       
where f2.milord = f1.milord)                                ";
    $r2 = db2_exec($con, $s2);
 //   var_dump($s2, db2_stmt_errormsg());
    $customers = '';
    $grade = '';
    $milords = ' ';
    $tetime = 0;
    while ($row2 = db2_fetch_assoc($r2)){
        $milord = $row2['MILORD'];
        $milords .= $milord . ', ';
        $ship = $row2['SHIP#'];
        $shipviatruck = false;
        $shipviarail = false;
        if (trim($grade) == '') $grade = $row2['GRD8'];
      
       $awpr = UPClass2::getAWPR($con, $grade);
       $tpp = UPClass2::getTPP($con, $grade);
       
         
        
        
        $s3 = "Select * from ordentp f1
left join ordatep f2 on f1.milord = f2.milord 
left join hdcust on CUSTSH = CMCUST 
where f1.MILORD = '$milord' and SHIP# = $ship";
        $r3 = db2_exec($con, $s3);
     //   var_dump($s3, db2_stmt_errormsg());
        $row3 = db2_fetch_assoc($r3);
        $grade = $row3['GRD8'];
        echo "<br>Grade: $grade : " . substr($grade,10,2);
        $rolls = $row3['ROLLS#'];
        $etime = UPClass2::getESTTIME($con, $grade, $rolls);
        $tetime += $etime;
        echo "<br> rolls: $rolls, AW/R: $awpr, TPP: $tpp estimated time: $etime <br>";
        if (trim($customers) == ''){
      $s3c = "Select CMCNA1  from ordentp                        
left join hdcust on CUSTSH = CMCUST where MILORD =  '$milord' ";
      $r3c = db2_exec($con, $s3c);
      while ($row3c = db2_fetch_assoc($r3c)){
        $customers .=  ' ' . $row3c['CMCNA1'] . ' -';
      }  
        }
        if (substr($grade,10,2) !== '01') $size = 'SIZE'; else $size = ' ';
        if (trim($row3['CARVIA']) == 'R') $shipviarail = true; else $shipviatruck = true;
    }
    
    if ($shipviarail and $shipviatruck) $shipvia = 'Truck/Rail'; 
    elseif ($shipviarail and !$shipviatruck) $shipvia = 'Rail';
    else $shipvia = 'Truck';
    
    $s4 = "Select min(
 cast(('20' || Ryear || '-' || Rmonth || '-' || rday)
 as date )) as ReqDate from milposp f1
 left join ordentp f2 on f2.milord = f1.milord
 left join ordatep f3 on f3.milord = f1.milord and f3.ship# =
f1.ship# WHERE MilRN# = $mr  ";
    $r4 = db2_exec($con, $s4);
    $row4=db2_fetch_assoc($r4);
//    var_dump($s4, db2_stmt_errormsg());
    $reqbydate = $row4['REQDATE'];
    
    $notes = $customers . ' / ' . $shipvia . ' / ' . $reqbydate;
    $sid = "Select max(Prod_ID) as PRODID from Prod_Schedule";
    $rid = db2_exec($con, $sid);
    $rowid = db2_fetch_assoc($rid);
    $nextid = $rowid['PRODID'] + 1;
    
 //   echo "<br>Rev: $rev, Run: $mr, Grade: $grade, Size: $size, Notes: $notes   , EstTim: $tetime";
    $s = "Insert into Prod_Schedule values(
      $rev,
     $mr,
     '$grade',
     '$size',
     '$notes',
     0,
     0,
     0,
     $tetime,
     '$reqbydate',
     current timestamp,
     current date,
     'Open',
     'N',
current time,
     $nextid,
     current timestamp,
     current timestamp,
     current timestamp,
     0) with NC";
$r = db2_exec($con, $s);
if (!$r){
var_dump($s, db2_stmt_errormsg());
} else {

UPClass2::setSchedule($con);

?>
<script>
window.opener.location.reload(true);
window.close();
</script>

<?php 


}
    } else {
        echo "<br> Mill run: $mr  Already exists on the production schedule or invalid";
    }
    
