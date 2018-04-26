<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include "../msConnect.php";
include "../UPHead.php";

require_once "TSClass.php";
require_once "UPClass2.php";

// ///////////////////////////////////////////////////////////////////////////////////////////////
// Check and reset values for toursheet
$sl = "select * from toursheet_time where timeid  = 22 and eventdate >= '2018-01-01'";
$rl = db2_exec($con, $sl);

while ($rowl = db2_fetch_assoc($rl)) {
    
    $reelid = $rowl['REELID'];
    $reel = $rowl['REEL'];
    if (strlen($reel) == 1)
        $reel = '0' . $reel;
    $reelnum = trim($reelid) . trim($reel);
    
    $note = '';
    
    $s = "select reelid, reel, grade, trim(Reelid)|| substr(digits(reel),4,2),
    (select
    trim(F_getPbyVN (trim(Reelid) ||substr(digits(reel),4,2),
    'Mill Run number' )) from prod_reel
    where eventname = (trim(reelid)||substr(digits(reel),4,2))
    fetch first 1 row only) as MR from toursheet
    where  reelid = '$reelid' and Reel = $reel";
    
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $millrun = $row['MR'];
    
    if (trim($millrun) == '') {
        $millrun = 'Not Found';
        $note = 'Mill Run number not found';
        $s2 = "SELECT DISTINCT (MILRUN) as MR FROM LPMAST WHERE
        SUBSTR(ROLLID, 1,5) = '$reelnum'  ";
        $r2 = db2_exec($con, $s2);
        $pu = '';
        $pud = '';
        while ($row2 = db2_fetch_assoc($r2)) {
            $pu .= $pud . $row2['MR'];
            $pud = '/';
        }
        if (trim($pu) !== '') {
            $note .= ' Produced Under ' . $pu;
        }
    }
    
    $s = "Select (Select trim(F_getPbyVN ('$reelnum','Mx Grade Code'))
    from Prod_Reel where eventname = '$reelnum' fetch first 1 row only) as Prod_Grade
    from toursheet  where  reelid >='$reelid' and reel = $reel";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $grade = '';
    $grade = $row['PROD_GRADE'];
    if (trim($grade) == '') {
        $grade = 'Not Found';
        $note .= " <br> MX Grade Code not found in Prod_Reel";
    }
    $grade = '';
    
    $s = "Select (Select trim(F_getPbyVN ('$reelnum','Grade Part 1')) ||
    trim(F_getPbyVN ('$reelnum','Grade Part 2')) ||
    trim(F_getPbyVN ('$reelnum','Grade Part 3')) ||
    trim(F_getPbyVN ('$reelnum','Grade Part 4'))
    from Prod_Reel where eventname = '$reelnum' fetch first 1 row only) as Prod_Grade
    from toursheet  where  reelid >='$reelid' and reel = $reel";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $grade = $row['PROD_GRADE'];
    if (trim($grade) == '') {
        $grade = 'Not Found';
        $note .= " <br> Grade parts 1, 4 not found in Prod_Reel";
    }
    
    $s = "Select (Select trim(F_getPbyVN ('$reelnum','Basis Weight F'))
    from Prod_Reel where eventname = '$reelnum' fetch first 1 row only) as Prod_BSWGT
    from toursheet  where  reelid >='$reelid' and reel = $reel";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $bswgt = '';
    $bswgt = $row['PROD_BSWGT'];
    if (trim($bswgt) == '') {
        $bswgt = 'Not Found';
        $note .= " <br> Base Weight F not found in Prod_Reel";
    }
    
    echo "<p>Reel: $reelid - $reel <br>.....Mill Run = $millrun";
    echo "<br>.....Grade = $grade ";
    echo "<br>.....Basis Weight = $bswgt ";
    
    echo '<br>' . $note;
    
    echo "<br> Loading data from Prod system for day/reel";
    // if (trim($note) !== ''){
    // $url = "http://10.6.1.11/IntraNet/UpPaper/Prod_Reel_Load.php?DB=PR&REEL=$reelnum";  
    UPClass2::ProdReelLoad($con, $dbh, $reelnum);
  
    echo "....... Clearing Toursheet Records .....";
    $s = "delete from toursheet where reelid = '$reelid'  and Reel = $reel with NC ";
    $r = db2_exec($con, $s);
    
    $s = "delete from toursheet_time f1 where not exists              
(Select * from toursheet f2 where f1.sheetid = f2.sheetid)  ";
    $r = db2_exec($con, $s);
    $s = "delete from toursheet_team f1 where not exists              
(Select * from toursheet f2 where f1.sheetid = f2.sheetid)  ";
    $r = db2_exec($con, $s);
    // }
    echo '</p>';
}

// ///////////////////////////////////////////////////////////////////////////////////////////////

$savreel = '';

$StartReel = 'A01';

$s = "select distinct cast(substr(rollid, 1,3) as char(3))          
from fmanstq.lpmast where                                     
(select cast(reelid as char(3)) as STARTREEL                               
 from fmanstq.toursheet_time where reelid <> ' '  order by sheetid desc            
fetch first 1 row only) <= cast(substr(rollid,1,3) as char(3))
fetch first 1 row only                                        
                                                          ";
$r = db2_exec($con, $s);
$row = db2_fetch_both($r);
// var_dump($row);
if (trim($row[0]) == '')
    $StartReel = 'U01';
else
    $StartReel = $row[0];
$StartReel = 'A01';

// ****************************************************************************************************************
$s = "Select * from FMANSTQ/prod_reel where
VARIABLE =  'Mx Order Number'  and substr(EVENTNAME, 1,3) >= '$StartReel' order by ENTRYON ";
$rloop = db2_exec($con, $s);
// var_dump($s, db2_stmt_errormsg());
// echo '<br>';
$shiftid = '';
$savedShift = '';
while ($row = db2_fetch_assoc($rloop)) {
    // ECHO '<br><br>';
    
    $ProdNum = $row['VALUE'];
    
    $reelid = substr($row['EVENTNAME'], 0, 3);
    $reelnum = substr($row['EVENTNAME'], 3, 2);
    $reelnum += 1;
    $reel = $row['EVENTNAME'];
    // $reel = trim($reelid) + $reelnum ; //$row['EVENTNAME'];
    $eventts = $row['ENTRYON'];
    $eventID = $row['ID'];
    $eventday = substr($eventts, 0, 10);
    $eventtime = substr($eventts, 11, 8);
    $sw = "SELECT  VALUE as WGT FROM FMANSTQ/prod_reel                              
where VARIABLE = 'Produced Tons'  and EVENTNAME = '$reel' ";
    $rw = db2_exec($con, $sw);
    $roww = db2_fetch_assoc($rw);
    $prod_wgt = $roww['WGT'];
    
    $s4 = "Select * from FMANSTQ/TOURSHEET_TIME where REELREF < '$reel' and TimeID = 1 order by reelref desc fetch first 1 row only ";
    $r4 = db2_exec($con, $s4);
    // var_dump( $s4, db2_stmt_errormsg());
    // echo "<br>";
    $row4 = db2_fetch_assoc($r4);
    $currentSheetId = $row4['SHEETID'];
    $closingreel = $row4['REELREF'];
    
    // var_dump($row4);
    // $closeRec = true; else $closeRec = false;
    // if (trim($currentSheetId) !== ''){
    // echo "<br> read: $reel closing: $closingreel Sheet:$currentSheetId";
    // closePrev($con, $reelid,$reelnum, $reel, $eventday, $eventtime, $eventID, $row4);
    // }
}
// ****************************************************************************************************************

// VARIABLE = '
// echo "Loading Timesheet ";
// Get records from PROD_REEL that do not exist Toursheet
$s = "Select * from FMANSTQ/prod_reel where                        
 VariableID = 283  and EVENTNAME >= 'A0101' and EVENTNAME <='Z9999'
and not exists (Select * from toursheet_time where reelref = 
substr(EVENTNAME,1,5))                                       
                                                             ";
$rloop = db2_exec($con, $s);
// var_dump($con, $rloop, $s, db2_stmt_errormsg());
// echo '<br>';
$newday = true;
$sheetid = 0;
while ($row = db2_fetch_assoc($rloop)) {
    // ECHO '<br>';
    
    $eventts = $row['ENTRYON'];
    $eventid = $row['EVENTID'];
    
    $s597 = "Select * from prod_reel where eventid = $eventid and variableid = 597";
    $r597 = db2_exec($con, $s);
    if (! $r597) {
        $ProdNum = $eventid;
    } else {
        $row597 = db2_fetch_assoc($r597);
        $ProdNum = $row597['VALUE'];
        if (trim($ProdNum) == '')
            $ProdNum = $eventid;
    }
    
    echo "<br> Prod Number:($eventid) $ProdNum / $eventid / " . $row['EVENTNAME'];
    
    $reelid = substr($row['EVENTNAME'], 0, 3);
    $reelnum = substr($row['EVENTNAME'], 3, 2);
    $reel = $row['EVENTNAME'];
    
    $eventID = $row['ID'];
    $eventday = substr($eventts, 0, 10);
    
    $eventtime = substr($eventts, 11, 8);
    $sd = "Select * from fmanstq/shiftschedule where shiftdate= '$eventday'";
    $rd = db2_exec($con, $sd);
    // var_dump($sd, db2_stmt_errormsg());
    $rowd = db2_fetch_assoc($rd);
    $dayshift = $rowd['SHIFTDAY'];
    $niteshift = $rowd['SHIFTNIGHT'];
    
    $sdate = strtotime($eventts . " - 1 day");
    // if the shift has changed from night to days and the last roll goes to the prevous shift;
    // Remember to set the shift ID to days after the insert
    $sdate = strtotime($eventts);
    $sd = date('Y-m-d', $sdate);
    $eyear = substr($sd, 0, 4);
    $emonth = substr($sd, 5, 2);
    $eday = substr($sd, 8, 2);
    
    if ($eventtime >= '06:00:00' and $eventtime < '18:00:00')
        $currentShift = 'Days';
    else
        $currentShift = 'Nites';
    if (trim($savedShift) !== trim($currentShift))
        $shiftchanged = true;
    else
        $shiftchanged = false;
    
    // echo "<br>" . trim(getMid($eyear, $emonth, $eday)) . sprintf('%02d', $eday). " ---- $reelnum --- $eventtime === $shiftchanged - $savedShift, $currentShift";
    
    if (($eventtime >= '06:00:00' and $eventtime < '18:00:00') and ! $shiftchanged) {
        // if ($newday and $sheetid >1) {
        // echo "<br> go back and to the last entry and change $sheetid";
        // $sb = "Update Toursheet_Time set reel = $reelnum,reelid = $reelid
        // where sheetid = $sheetid";
        // $rb = db2_exec($con, $s);
        //
        // $newday = false;
        // }
        
        $sdate = strtotime($eventts);
        $sd = date('Y-m-d', $sdate);
        $shift = 'Day ' . date('m/d', $sdate);
        $st = '06:00:00';
        $ed = $sd;
        $et = '17:59:59';
        $shiftid = $dayshift;
    } else {
        
        // if ($eventtime < '09:00:00') {
        // $sdate = strtotime($eventts . "-1 day");
        // $edate = strtotime( $eventts);
        // $shift = 'Nite ' . date('m/d', $sdate);
        // $shiftid = $niteshift;
        // }
        
        if ($eventtime >= '00:00:01' and $eventtime < '09:00:00') {
            
            $sdate = strtotime($eventts . " - 24 hours");
            $edate = strtotime($eventts);
        } else {
            $sdate = strtotime($eventts);
            $edate = strtotime($eventts . " + 24 hours");
        }
        $shift = 'Nite ' . date('m/d', $sdate);
        $shiftid = $niteshift;
        // }
        $sd = date('Y-m-d', $sdate);
        $st = '18:00:00';
        
        $ed = date('Y-m-d', $edate);
        $et = '05:59:59';
        $newday = true;
        // $shiftid = $niteshift;
    }
    
    $eyear = substr($sd, 0, 4);
    $emonth = substr($sd, 5, 2);
    $eday = substr($sd, 8, 2);
    
    $mid = trim(getMid($eyear, $emonth, $eday));
    // if ($reelnum == 1){
    // echo "<br> go back and to the last entry and change $eventID";
    // $sb = "Select * from Toursheet_time where EventDate < '$eventday' and timeid = 1 order by EventDate Desc fetch first 1 row only";
    // $rb = db2_exec($con, $sb);
    // var_dump($sb, db2_stmt_errormsg());
    // $rowb = db2_fetch_assoc($rb);
    // echo "<br> go back and to the last entry and change " . $rowb['SHEETID'];
    
    // }
    
    $newReelNum = $reelnum;
    $newReelId = $reelid;
    $newReel = $newReelId . sprintf('%02d', $newReelNum);
    
    // echo "<br>EventName: $reel, Event Date: $eventday - $eventtime Shift Start Date: $sd Month ID = $mid - $eventts --- $newReel";
    
    // $newReelId = trim(getMid($eyear, $emonth, $eday)) . sprintf('%02d', $eday);
    // if (substr($reel,0,3) == trim($newReelId)){
    // $newReelNum = $reelnum + 1;
    // } else {
    // // $newReelNum = 1;
    // $newReelNum = $reelnum + 1;
    // }
    
    // if (substr($reel,0,3) !== trim($newReelId)) $newday = 'New'; else $newday = 'Old';
    
    // $newReel = $newReelId . sprintf("%02d", $newReelNum);
    // Echo '<br>Event ID: ' . $reel . ' Last Reel: ' . substr($reel,0,3) . ' New Reel: ' . $newReelId . ' - ' . $newday . ' Event Time: ' . $eventtime . ' Last Reel: ' . $reelnum . " New #: " . $newReelNum . " $newReel";
    
    // do we have a record for this reel
    // $s4 = "Select SHEETID from FMANSTQ/TOURSHEET_TIME where REELID = '$reelid' and REEL = $reelnum and timeid = 999";
    // $r4 = db2_exec($con, $s4);
    // $row4 = db2_fetch_assoc($r4);
    // $currentSheetId = $row4['SHEETID'];
    // if (trim($currentSheetId) !== '') $closeRec = true; else $closeRec = false;
    // if (!$closeRec){
    $s4 = "Select * from FMANSTQ/TOURSHEET_TIME where REELID = '$newReelId' and REEL = $newReelNum and timeid = 1";
    $r4 = db2_exec($con, $s4);
    $rowOpen = db2_fetch_assoc($r4);
    $openSheetId = $rowOpen['SHEETID'];
    // }
    if (trim($openSheetId) !== '')
        $openRec = true;
    else
        $openRec = false;
    // echo 'Reel: ' . $reel . ' Event Date: ' . $eventts . ' ' . $reelid . '-' . $reelnum . ' Run: ' . $ProdNum . ' Rows: ' . $rcnt;
    // echo '<br>';
    // process change - when we get a record the reel is being completed - need create a new set of records for the next reel.
    // echo"<br> Event: $reel - $reelnum, Current Sheet: $currentSheetId, Open Rec: $openRec, Closed Rec: $closeRec";
    
    // New order
    // if (!$closeRec and $openRec){
    // $openReelid = $rowOpen['REELID'];
    // $openReelNum = $rowOpen['REEL'];
    // $openReel = trim($openReelid) . $openReelNum;
    // $openEventDay = $openRow['EVENTDATE'];
    // $openEventTime = $openRow['EVENTTIME'];
    // $openEventID = $openRow['EVENTID'];
    // closePrev($con, $openReelid,$openReelNum, $openReel, $openEventDay, $openEventTime, $openEventID);
    // }
    // $openRec = true;
    if (! $openRec) {
        // echo "<br><b>Create new Record $newReelId = $newReelNum</b>";
        $s = "Select Max(SHEETID) as SID  from FMANSTQ.Toursheet";
        $r = db2_exec($con, $s);
        // // var_dump($s, db2_stmt_errormsg());
        // echo '<br>';
        $row = db2_fetch_assoc($r);
        $sheetid = $row['SID'] + 1;
        
        // need the grade and bases weight
        
        $s = "INSERT INTO FMANSTQ/TOURSHEET_TEAM (SHEETID, SHEETDATE, REELID, REEL) VALUES($sheetid,'$eventday', '$newReelId', $newReelNum) with NC";
        $r = db2_exec($con, $s);
        if (!$r)
            var_dump($s, db2_stmt_errormsg());
        
        // echo '<br>';
        $s = "INSERT INTO FMANSTQ/TOURSHEET (SHEETID, REELID, REEL) VALUES($sheetid, '$newReelId', $newReelNum) with NC";
        $r = db2_exec($con, $s);
        if (!$r)
            var_dump($s, db2_stmt_errormsg());
        // var_dump($s, db2_stmt_errormsg());
        // echo '<br>';
        $s = "INSERT INTO FMANSTQ/TOURSHEET_TIME (SHEETID,  TIMECODE,
     EVENTDATE, EVENTTIME, EVENTCOMMENT, TIMECAT, TIMEID, EVENTID, REELREF, REEL, REELID) VALUES($sheetid, 'Reel Drop',
     '$eventday', '$eventtime', 'Reel Drop', 'Process', 1,$eventID, '$newReel', $newReelNum, '$newReelId') with NC  ";
        $r = db2_exec($con, $s);
        // var_dump($s, db2_stmt_errormsg());
        // var_dump($s, db2_stmt_errormsg());
        // var_dump($s, db2_stmt_errormsg());
        // echo "<br> Processing Reel: $reel";
        // echo '<br>';
        // Echo "<br> Adding records for sheet: $sheetid";
        
        // need the grade and bases weight
        $s2 = "Select Value from PROD_REEL where EVENTNAME = '$reel' and variable = 'Mx Grade Code' ";
        $r2 = db2_exec($con, $s2);
        $row2 = db2_fetch_assoc($r2);
        $grade = $row2['VALUE'];
        
        if (trim($grade) == ''){
            $s2 = "Select (Select trim(F_getPbyVN ('$reelnum','Grade Part 1')) ||
            trim(F_getPbyVN ('$reelnum','Grade Part 2')) ||
            trim(F_getPbyVN ('$reelnum','Grade Part 3')) ||
            trim(F_getPbyVN ('$reelnum','Grade Part 4'))
            from Prod_Reel where eventname = '$reelnum' fetch first 1 row only) as Prod_Grade
            from toursheet  where  reelid >='$reelid' and reel = $reel";
            $r2 = db2_exec($con, $s2);
            $row2 = db2_fetch_assoc($r2);
            $grade = $row2['PROD_GRADE'];
            
        }
        
        
        
        $bw = substr($grade, 0, 2);
        
        if (trim($ProdNum) == '')
            $ProdNum = '999' . $sheetid;
        
        $s = "Update  FMANSTQ/TOURSHEET set PRODORD = $ProdNum, MILRUN = $ProdNum,  GRADE = '$grade', BSWGT = '$bw'
     Where SHEETID = $sheetid with NC";
        $r = db2_exec($con, $s);
        
        // var_dump($s, db2_stmt_errormsg());
        
        // $s = "Update FMANSTQ/TOURSHEET_TIME set TIMECODE = 'New Reel', EVENTDATE = '$eventday',
        // EVENTTIME = '$eventtime', EVENTDUR = 0 where sheetid = $sheetid with NC";
        // $r = db2_exec($con, $s);
        // var_dump($s, db2_stmt_errormsg());
        // echo '<br>';
        
        // LETS UPDATE THE SHIFT INFO WITH DEFAULT DATA
        // FIRST GET THE SHIFT ASSIGNED
        
        // what is the evnent shift day or night
        
        $shiftid = $dayshift;
        if (trim($savreel) == '')
            $savreel = $reelid;
        if (trim($reelid) == trim($savreel))
            $nextday = true;
        else
            $nextday = false;
        if (trim($reelid) == trim($savreel) and $reel !== 1 and $eventtime < '07:00:00')
            $nextday = false;
        if (trim($reelid) !== trim($savreel) and $reel !== 1)
            $nextday = true;
        
        // echo "<br>set shift for sheet $sheetid for shift $shiftid";
        setshift($con, $sheetid, $shiftid);
        $savreel = $reelid;
        $s3 = "Update Toursheet_team set SCHEDULEDHOURS = 12, SHIFTSTARTDATE = '$sd', SHIFTSTARTTIME = '$st', SHIFTENDDATE = '$ed', SHIFTENDTIME = '$et' ,
SHIFT = '$shift' where SHEETID = $sheetid with NC";
        $r3 = db2_exec($con, $s3);
        
        if (trim($grade) == '') {
            // $s = "Delete from FMANSTQ.toursheet_team WHERE SHEETID = $sheetid";
            // $r = db2_exec($con, $s);
            // $s = "Delete from FMANSTQ.toursheet_time WHERE SHEETID = $sheetid";
            // $r = db2_exec($con, $s);
            // $s = "Delete from FMANSTQ.toursheet WHERE SHEETID = $sheetid";
            // $r = db2_exec($con, $s);
        }
    } // end rcount
    
    $shiftcnt += 1;
    $savedShift = $currentShift;
} // end loop
  echo "<br> Adding Downtime ";
// we need to add any scheduled down time to the most current timesheet.
$s = "Select max(sheetid) as SHEETID from Fmanstq.Toursheet";
$r = db2_exec($con, $s);
$row = db2_fetch_assoc($r);

$lastSheet = $row['SHEETID'];
$s = "Select * from Fmanstq.Toursheet_time where sheetid = $lastSheet and TimeID = 1"; // get the first time date/time
$r = db2_exec($con, $s);
$lastRow = db2_fetch_assoc($r);
$eventDay = $lastRow['EVENTDATE'];
echo " Event Date: $eventDay";
if (trim($eventDay) !== '') {
    $s = "Select * from Fmanstq.shiftschedule where shiftdate >= '$eventDay' and shiftdate <= current date and scheddown = 'Y'";
    $r = db2_exec($con, $s);
    if (! $r) {
        var_dump($s, db2_stmt_errormsg());
    } else {
        while ($row = db2_fetch_assoc($r)) {
            $rcnt = 0;
            $shiftDate = $row['SHIFTDATE'];
            $downdur = $row['MAINTDUR'];
            $s2 = "Select count(*) as RCNT from Fmanstq.Toursheet_time where sheetid = $lastSheet and EventDate = '$shiftDate' and TimeCode = 'Sched Down'";
            $r2 = db2_exec($con, $s2);
            $row2 = db2_fetch_assoc($r2);
            $rcnt = $row2['RCNT'];
            echo " - Record count $rcnt";
            if ($rcnt == 0) {
                $s4 = "Select max(TIMEID) as TID from Fmanstq.TOURSHEET_TIME where SHEETID = $lastSheet";
                $r4 = db2_exec($con, $s);
                $row4 = db2_fetch_assoc($r4);
                $nextTID = 1 + $row4['TID'];
                $s3 = "INSERT INTO FMANSTQ/TOURSHEET_TIME (SHEETID,  TIMECODE,
            EVENTDATE, EVENTDUR, EVENTCOMMENT, TIMECAT, TIMEID, PROD_WGT) VALUES($lastSheet, 'Sched Down',
            '$shiftDate', $downdur, $prod_wgt,'Scheduled down time for $downdur min.', 'Down', $nextTID) with NC  ";
                $r3 = db2_exec($con, $s3);
                // var_dump($s4, db2_stmt_errormsg());
            }
            // need to add a record to the Prod_Schedule for downtime if one does not exist
        }
    }
}

$s = "update tours00002 set prod_wgt = cast(                       
 (SELECT  VALUE as WGT FROM FMANSTQ/prod_reel                
where VARIABLE = 'Produced Tons'  and EVENTNAME = reelref    
fetch first 1 row only) as dec(10,2))                        
                                                             
where prod_wgt = 0                                           
and reelid <> ' '  and                                       
exists                                                       
   (SELECT  VALUE  FROM FMANSTQ/prod_reel                    
  where VARIABLE = 'Produced Tons'  and EVENTNAME = reelref) 
and                                                          
   (SELECT  VALUE  FROM FMANSTQ/prod_reel                    
  where VARIABLE = 'Produced Tons'  and EVENTNAME = reelref  
fetch first 1 row only )                                     
    <> ' '                                                    ";
$r = db2_exec($con, $s);

// $tsid = TSCass::newReel($con, $rollid, $prodord);

// lets make sure the grade has been added to the oddball records if possible (grade VID = 595
$s = "select * from toursheet where grade = ' ' and milrun > 0";
$r = db2_exec($con, $s);
while ($row = db2_fetch_assoc($r)) {
    $v = '';
    $id = $row['MILRUN'];
    $sheetid = $row['SHEETID'];
    $row2 = array();
    $x['VALUE'] = '';
    $row2[] = $x;
    $s2 = "Select * from prod_reel where eventid = $id and variableid = 595";
    $r2 = db2_exec($con, $s);
    $row2 = db2_fetch_assoc($r2);
    
    if (array_key_exists("VALUE", $row2)) {
        $v = $row2['VALUE'];
        $s3 = "Update TOURSHEET set GRADE = '$v', timeid = 1, TIMECAT ='Reel Drop' , Timecode = 'Process' where sheetid = $sheetid with NC";
        $r3 = db2_exec($con, $s3);
        // var_dump($s3, db2_stmt_errormsg());
    } else {
        $s3 = "Update TOURSHEET_TIME set timeid = 22, TIMECAT = 'BadData', timecode = 'BadData'  where sheetid = $sheetid with NC";
        $r3 = db2_exec($con, $s3);
        // var_dump($s3, db2_stmt_errormsg());
    }
}

$s = "Update toursheet set Milrun =                                
 (select                                                     
trim(F_getPbyVN (trim(Reelid) ||substr(digits(reel),4,2),    
'Mill Run number' )) from prod_reel                          
where eventname = (trim(reelid)||substr(digits(reel),4,2))   
fetch first 1 row only)                                      
where    milrun < 100 and                                    
  (select                                                    
trim(F_getPbyVN (trim(Reelid) ||substr(digits(reel),4,2),    
 'Mill Run number' )) from prod_reel                         
 where eventname = (trim(reelid)||substr(digits(reel),4,2))  
 fetch first 1 row only) is not null  and                    
  (select                                                    
trim(F_getPbyVN (trim(Reelid) ||substr(digits(reel),4,2),    
 'Mill Run number' )) from prod_reel                         
 where eventname = (trim(reelid)||substr(digits(reel),4,2))  
 fetch first 1 row only)  > '100'       with NC                     ";
db2_exec($con, $s);

function setshift($con, $sheetid, $shiftid)
{
    // has the shift been defined?
    $s = "Select * from FMANSTQ/TOURSHEET_TEAM WHERE SHEETID = $sheetid";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    if (trim($row['BACKTENDER']) == '') {
        $s = "Select * from FManstq.EmpSchedule where shiftid = '$shiftid'";
        $r = db2_exec($con, $s);
        // var_dump($s, db2_stmt_errormsg());
        $row = db2_fetch_assoc($r);
        $sup = $row['SUPERVISOR'];
        $lab = $row['LAB'];
        $mt = $row['MACHTENDER'];
        $bt = $row['BACKTENDER'];
        $h3 = $row['THIRDHAND'];
        $h4 = $row['FOURTHHAND'];
        $h5 = $row['FIFTHHAND'];
        $s = "Update fmanstq/toursheet_team set
SHIFT = '$shiftid',
SUPERVISOR = '$sup',
LAB = '$lab',
MACHTENDER = '$mt',
BACKTENDER = '$bt',
THIRDHAND = '$h3',
FORTHHAND = '$h4',
FIFTHHAND = '$h5'
where sheetid = $sheetid;
";
        $r = db2_exec($con, $s);
        // var_dump($s, db2_stmt_errormsg());
    }
}

function closeprev($con, $reelid, $reel, $rid, $eventday, $eventtime, $eventID, $row)
{
    
    // Echo "<br> .....Reel ID: $rid";
    $pid = $row['SHEETID'];
    $preel = $row['REEL'];
    $preelref = $row['REELREF'];
    if (trim($pid) !== '') {
        // echo '<br>..........Close prevous record: ' . $pid ;
        $s = "INSERT INTO FMANSTQ/TOURSHEET_TIME (SHEETID,  TIMECODE,
    EVENTDATE, EVENTTIME, EVENTCOMMENT, TIMECAT, TIMEID, EVENTID, REELREF) VALUES($pid, 'Reel End',
    '$eventday', '$eventtime', 'Close Reel', 'Process', 999, $eventID, '$preelref') with NC  ";
        $r = db2_exec($con, $s);
        // var_dump($r, $s, db2_stmt_errormsg());
        $s = "select timestampdiff(4, char( timestamp(eventdate, eventtime) -
    (select timestamp(eventdate,eventtime) from toursheet_time
    where sheetid = $pid and timeid = 1))) as DIFF
    from
    toursheet_time  where sheetid = $pid and timeid = 999               ";
        $r = db2_exec($con, $s);
        
        $rowdiff = db2_fetch_assoc($r);
        $diff = $rowdiff['DIFF'];
        if ($diff < 0)
            $diff = $diff * - 1;
        // lets set the reel, and reelid where they are zero for this work sheet
        $s = "Update Fmanstq/Toursheet_Time set REELID = '$reelid', REEL= $preel where SHEETID = $pid and (REELID = ' ' or REEL=0)";
        $r = db2_exec($con, $s);
        
        // check to see if the event date from the production system is less than the date of time id 1
        // $sd = "select * from fmanstq.toursheet_time where sheetid = $pid and timeid = 1 ";
        // $rd = db2_exec($con, $s);
        // $rowd = db2_fetch_assoc($rd);
        // var_dump($sd, db2_stmt_errormsg(), $rowd );
        // $EventDate1 = $rowd['EVENTDATE'];
        // if ($EventDate1 > $eventday) $eventday = $EventDate1;
        
        // echo '<br><b>Roll Duration: ' . $diff . '</b><br>';
        $s = "Update FMANSTQ.TOURSHEET_time set EVENTDUR = $diff, EVENTDATE = '$eventday' where sheetid = $pid and timeid = 999 with NC";
        $r = db2_exec($con, $s);
    }
}

function getMid($y, $m, $d)
{
    $peo = ($y % 2 == true ? 'odd' : 'even');
    // echo " - Year is $peo";
    if ($peo == 'even') {
        switch ($m) {
            
            case "01":
                $mid = "A";
                break;
            case "02":
                $mid = "B";
                break;
            case "03":
                $mid = "C";
                break;
            case "04":
                $mid = "D";
                break;
            case "05":
                $mid = "E";
                break;
            case "06":
                $mid = "F";
                break;
            case "07":
                $mid = "G";
                break;
            case "08":
                $mid = "H";
                break;
            case "09":
                $mid = "I";
                break;
            case "10":
                $mid = "J";
                break;
            case "11":
                $mid = "K";
                break;
            case "12":
                $mid = "L";
                break;
        }
    } else {
        switch ($m) {
            
            case "01":
                $mid = "M";
                break;
            case "02":
                $mid = "N";
                break;
            case "03":
                $mid = "P";
                break;
            case "04":
                $mid = "Q";
                break;
            case "05":
                $mid = "R";
                break;
            case "06":
                $mid = "S";
                break;
            case "07":
                $mid = "T";
                break;
            case "08":
                $mid = "U";
                break;
            case "09":
                $mid = "W";
                break;
            case "10":
                $mid = "X";
                break;
            case "11":
                $mid = "Y";
                break;
            case "12":
                $mid = "Z";
                break;
        }
    }
    
    return $mid;
}
