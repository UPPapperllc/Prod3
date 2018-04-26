<?php

class UPClass2
{

    public static function getCurrentUser($con)
    {
        $user = strtoupper($_SERVER['PHP_AUTH_USER']);
        $s2 = "Select * from PRHDSDATA/SYUSER where USUSER = '$user'";
        $r2 = db2_exec($con, $s2);
        $row2 = db2_fetch_assoc($r2);
        $x['USERID'] = $user;
        $x['USERNAME'] = $row2['USDESC'];
        $x['CLOCK'] = $row2['USCLCK'];
        $x['EMAIL'] = $row2['USEMAL'];
        $x['ROLE'] = $row2['USROLE'];
        $x['DFTCOA'] = $row2['USCATN'];
        $x['SECLEVEL'] = substr($row2['USFAX'], 0, 3);
        $x['COASEQ'] = $row2['USDPLT'];
        return $x;
        // Model: USERID,USERNAME,CLOCK,EMAIL,ROLE
    }

    public static function getCOASeq($con, $next, $user)
    {
        $s = "Select USDPLT from PRHDSDATA/SYUSER where USUSER = '$user'";
        $r = db2_exec($con, $s);
        $userrow = db2_fetch_assoc($r);
        $coaseq = $userrow['USDPLT'];
        if ($next) {
            $coaseq += 1;
            if ($coaseq >= 990)
                $coaseq = 0;
            $s = "Update PRHDSDATA/SYUSER SET USDPLT = $coaseq";
            $r = db2_exec($con, $s);
        }
        $usr_report = trim($user) . '-' . $coaseq;
        return $usr_report;
    }

    public static function loadCOAReport($con, $usr, $milord, $shipnum, $email, $report)
    {
        $data = array();
        $c = 0;
        $user = $usr;
        
        $usr_report = trim($usr) . trim($report);
        require_once "Classes/UPClass2.php";
        
        $usr_report = UPClass2::getCOAseq($con, true, $user);
        
        // $report = 'TEST01';
        $filename = 'PROD_' . trim($report);
        
        // Level break on rollid;
        $savrollid = '';
        // select the subset of data based on the report ID
        $s = "select * from Prod_Reel2 
where testrpt = '$report'  and MILORD='$milord' and shipnum=$shipnum 
and (lpstat <> 'Culled' and lpstat <> 'Deleted') order by rollid";
        $r = db2_exec($con, $s);
        if (! $r) {
            $data['failure'] = true;
            $data['failure']['sql'] = $s;
            $data['failure']['errormsg'] = db2_stmt_errormsg();
        }
        $data['dataloadstarted'] = date("h:i:sa");
        while ($row = db2_fetch_assoc($r)) {
            // first record
            if (trim($savrollid) == '') {
                $savrollid = $row['ROLLID'];
                $firstrec = true;
            }
            // level break
            $rollid = $row['ROLLID'];
            
            if (trim($rollid) !== trim($savrollid)) {
                // // // // // // var_dump($rollid, $savrollid);
                $firstrec = true;
                $savrollid = $rollid;
            }
            
            // if the first record for a roll create the record;
            if ($firstrec) {
                // // // // // // var_dump($row);
                $s1 = "INSERT INTO $filename (USR, RPT, ID, ROLLID) VALUES('" . $usr_report . "', '" . $report . "'," . $row['ID'] . ", '" . $row['ROLLID'] . "')";
                $r1 = db2_exec($con, $s1);
                // // // // // // var_dump($r1, $s1, db2_stmt_errormsg());
                $firstrec = false;
            }
            // update the field in the file with value;
            $fieldLRL = 'F' . trim($row['VID']) . '_LRL';
            $fieldLUL = 'F' . trim($row['VID']) . '_LUL';
            $fieldLWL = 'F' . trim($row['VID']) . '_LWL';
            $fieldUEL = 'F' . trim($row['VID']) . '_UEL';
            $fieldURL = 'F' . trim($row['VID']) . '_URL';
            $fieldUUL = 'F' . trim($row['VID']) . '_UUL';
            $fieldUWL = 'F' . trim($row['VID']) . '_UWL';
            $fieldVALUE = 'F' . trim($row['VID']) . '_VALUE';
            $fieldTARGET = 'F' . trim($row['VID']) . '_TARGET';
            
            if (trim($row['REPORTDEC']) !== '0' && trim($row['VALUE']) !== '') {
                $value = number_format(floatval($row['VALUE']), $row['REPORTDEC']);
            } else
                $value = $row['VALUE'];
            
            if (trim($row['REPORTDEC']) !== '0' && trim($row['LRL']) !== '') {
                $LRL = number_format(floatval($row['LRL']), $row['REPORTDEC']);
            } else
                $LRL = $row['LRL'];
            
            if (trim($row['REPORTDEC']) !== '0' && trim($row['TGT']) !== '') {
                $TARGET = number_format(floatval($row['TGT']), $row['REPORTDEC']);
            } else
                $TARGET = $row['TGT'];
            
            if (trim($row['REPORTDEC']) !== '0' && trim($row['LUL']) !== '') {
                $LUL = number_format(floatval($row['LUL']), $row['REPORTDEC']);
            } else
                $LUL = $row['LUL'];
            
            if (trim($row['REPORTDEC']) !== '0' && trim($row['LWL']) !== '') {
                $LWL = number_format(floatval($row['LWL']), $row['REPORTDEC']);
            } else
                $LWL = $row['LWL'];
            
            if (trim($row['REPORTDEC']) !== '0' && trim($row['UEL']) !== '') {
                $UEL = number_format(floatval($row['UEL']), $row['REPORTDEC']);
            } else
                $UEL = $row['UEL'];
            
            if (trim($row['REPORTDEC']) !== '0' && trim($row['URL']) !== '') {
                $URL = number_format(floatval($row['URL']), $row['REPORTDEC']);
            } else
                $URL = $row['URL'];
            
            if (trim($row['REPORTDEC']) !== '0' && trim($row['UUL']) !== '') {
                $UUL = number_format(floatval($row['UUL']), $row['REPORTDEC']);
            } else
                $UUL = $row['UUL'];
            
            if (trim($row['REPORTDEC']) !== '0' && trim($row['UWL']) !== '') {
                $UWL = number_format(floatval($row['UWL']), $row['REPORTDEC']);
            } else
                $UWL = $row['UWL'];
            
            $s2 = "Update $filename 
set $fieldVALUE = '" . $value . "', 
	$fieldLRL = '" . $LRL . "',
	$fieldLUL = '" . $LUL . "',
	$fieldLWL = '" . $LWL . "',
	$fieldUEL = '" . $UEL . "',
	$fieldURL = '" . $URL . "',
	$fieldUUL = '" . $UUL . "',
	$fieldTARGET = '" . $TARGET . "',
	$fieldUWL = '" . $UWL . "'
	 where ROLLID = '$rollid' and USR = '$usr_report' and RPT = '$report'";
            $r2 = db2_exec($con, $s2);
            // // // // // // var_dump($s, db2_stmt_errormsg());
            // $x['Load']['ROLLID'] = $row['ROLLID'];
            // $x['Load']['TESTID'] = $row['VID'];
            // $x['Load']['TESTDESC'] = $row['VDESC'];
            // $x['Load'][$fieldVALUE] = $value;
            
            // $data['root'][$c]= $x;
            $c += 1;
        }
        
        $data['dataloadended'] = date("h:i:sa");
        $data['reportstarted'] = date("h:i:sa");
        
        $data['success'] = true;
        
        return $data;
    }
    // *******************************************************************
    public static function coaValidat($con, $user, $report, $dbh)
    {
        $usr_report = UPClass2::getCOAseq($con, false, $user);
        include "../../msConnect.php";
        include "../../UPHead.php";
        // $report = 'TEST01';
        $filename = 'PROD_' . trim($report);
        $data = array();
        $c = 0;
        
        $s1 = "select distinct substr(rollid,1,5) as REEL from prod_coa 
where usr = 'JMATZ-820'                           ";
        $r1 = db2_exec($con, $s1);
        while ($row = db2_fetch_assoc($r1)) {
            
            $reel = $row['REEL'];
            
            // Prod_Reel should only have 1 value for each of reel
            $s2 = 'Select TGT, LRL, URL,"Value", VariableId  from SDK_V_PAVariableResultEvent_UP where EventName =' . "'$reel'
 	  and  EntryOn <> ResultOn ";
            $r2 = $dbh->query($s2);
            if (! $r2) {
                // // // // // // var_dump($dbh->errorInfo());
            } else {
                
                while ($row2 = $r2->fetch(PDO::FETCH_NUM)) {
                    ;
                    // // // // // // var_dump($row2);
                    $value = $row2[3];
                    $tgt = $row2[0];
                    $LRL = $row2[1];
                    $URL = $row2[2];
                    $vid = $row2[4];
                    
                    $fieldLRL = 'F' . trim($vid) . '_LRL';
                    $fieldLUL = 'F' . trim($vid) . '_LUL';
                    $fieldLWL = 'F' . trim($vid) . '_LWL';
                    $fieldUEL = 'F' . trim($vid) . '_UEL';
                    $fieldURL = 'F' . trim($vid) . '_URL';
                    $fieldUUL = 'F' . trim($vid) . '_UUL';
                    $fieldUWL = 'F' . trim($vid) . '_UWL';
                    $fieldVALUE = 'F' . trim($vid) . '_VALUE';
                    $fieldTARGET = 'F' . trim($vid) . '_TARGET';
                    $sx = " SELECT VID  FROM prod_var_R WHERE testrpt = '$report' and VID = $vid";
                    $rx = db2_exec($con, $sx);
                    $rowx = db2_fetch_assoc($rx);
                    if (trim($rowx['VID']) !== '') {
                        
                        $s3 = "Update $filename set 
 	 $fieldVALUE = '" . $value . "', 
	$fieldLRL = '" . $LRL . "',
	$fieldURL = '" . $URL . "',
	$fieldTARGET = '" . $tgt . "'
 	where ROLLID like '$reel%' and USR = '$usr_report' and RPT = '$report'
 	";
                        $r3 = db2_exec($con, $s3);
                        $s4 = "Update Prod_Reel set Value = '$value' where EventName = '$reel' and VariableId = $vid";
                        $r4 = db2_exec($con, $s4);
                        // and (value <> '$value' or TGT <> '$tgt' or LRL <> '$LRL' or URL <> '$URL')
                        // // // // // // var_dump($s3, db2_stmt_errormsg());
                    }
                }
            }
        }
        $data['success'] = true;
        return $data;
    }
    // *****************************************************************
    public static function getMid($y, $m, $d)
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
                    $mid = "V";
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

    public static function getNextProdTime($con)
    {
        // get the last record
        $s1 = "Select * from PROD_SCHEDULE  where milrun <> 200    
order by SCHets desc                                
fetch first 1 row only                              ";
        $r1 = db2_exec($con, $s1);
        $row1 = db2_fetch_assoc($r1);
        if (! $r1 or trim($row1['PROD_ID']) == '') {
            $x['DAY'] = date('Y-m-d', date());
            $x['TIME'] = date('H:i:s', date());
        } else {
            $new_etime = strtotime($row1['SCHDATE'] . ' ' . $row1['SCHTIME']) + (($row1['ESTHOURS'] + .01) * 60 * 60);
            $x['DAY'] = date('Y-m-d', $new_etime);
            $x['TIME'] = date('H:i:s', $new_etime);
        }
        
        return $x;
    }

    public static function getNextProdId($con)
    {
        $sm = "Select max(prod_id)as ID from Prod_Schedule";
        $rm = db2_exec($con, $sm);
        $rowm = db2_fetch_assoc($rm);
        $id = $rowm['ID'];
        $id += 1;
        return $id;
    }
    // ****************************************************************************************************
    //** Add an entry to the production schedule
    // ****************************************************************************************************
    public static function addProdSchedEvent($con, $id, $outage, $rev, $run, $grd, $info, $notes, $ehours, $sday, $stime, $stat)
    {
        $refid = 0;  // Reference Run Number for linked events
        $inclwip = false;  // Include WIP in Adjustment Calculation 
        // outage values 0 = new standard, 1 planned event, 2 un-planned event - 3 other event - 6 Non Schedule related Event
        if (trim($outage) > 0) {
            // $id = $_GET['ID'];
            $notes = trim($notes) . ' Event Extra Time for ' . $run;
            $refid = $id;
            $run = $outage * 100;
            
            $inclwip = true;
            // Update the production line for the parrent run number 
            $su = "Update prod_schedule set EstHours = EstHours + $ehours , schets =    TIMESTAMP(SCHDATE, SCHTIME) + (ESTHOURS * 60) MINUTES where Prod_id = $refid";
           // $ru = db2_exec($con, $su);
           // $x = UPClass2::resetProdSched($con, $refid, $inclwip);
        }
        // get the run order information
        // $s = "Select * from MILRUNP where Milrun = $mr";
        // $r = db2_exec($con, $s);
        // $row = db2_fetch_assoc($r);
        
        $rev = 1;  // Default Revision Number
        $id = UPClass2::getNextProdId($con);   // Get the New ID number
        //  If the start day is blank get the next production date/time to append this entry at the end of the scedule  
        if (trim($sday) == '') {
            $dt = UPClass2::getNextProdTime($con);
            $day = $dt['DAY'];
            $stime = $dt['TIME'];
        } else
            $day = $sday;
        
            
       // Insert the production schedule 
        $s = "Insert into prod_schedule values(
    $rev,
    $run,
    '$grd',
    '$info',
    '$notes',
    0,
    0,
    0,
    $ehours,
    ' ',
    '0001-01-01-00.00.00.000000',
    '$day',
    'Open',
    ' ',
    '$stime',
    $id,
    current timestamp,
    current timestamp,
    current timestamp,
    $refid
    )  with NC";
        
        $r = db2_exec($con, $s);
        $x['qryInsert'] = $s;
        $x['qryInsertMsg'] = db2_stmt_errormsg();
        // // // // // // var_dump($s, db2_stmt_errormsg());
        
        // We default the schedule start and end time stamps  - then update them next 
        
        $su = "Update Prod_Schedule set
    SCHTS = TIMESTAMP(SCHDATE, SCHTIME),
    SCHETS = TIMESTAMP(SCHDATE, SCHTIME) + (ESTHOURS * 60) MINUTES
    where PROD_ID = $id with NC";
        $ru = db2_exec($con, $su);
        $x['ID'] = $id;
        $inclwip = true;
        UPClass2::setSchedule($con);
        return $x;
    }
    // ****************************************************************************************************
    //** Add an entry to the production schedule
    // ****************************************************************************************************
    public static function addProdSchedEventV2($con, $id, $outage, $rev, $run, $grd, $info, $notes, $ehours, $sts, $stat, $ets)
    {
        $refid = 0;  // Reference Run Number for linked events
        $inclwip = false;  // Include WIP in Adjustment Calculation
        // outage values 0 = new standard, 1 planned event, 2 un-planned event - 3 other event - 6 Non Schedule related Event
        if (trim($outage) > 0) {
            // $id = $_GET['ID'];
            $notes = trim($notes) . ' Event Extra Time for ' . $run;
            $refid = $id;
            $run = $outage * 100;
    
            $inclwip = true;
            // Update the production line for the parrent run number
            $su = "Update prod_schedule set EstHours = EstHours + $ehours , schets =    TIMESTAMP(SCHDATE, SCHTIME) + (ESTHOURS * 60) MINUTES where Prod_id = $refid";
         //   $ru = db2_exec($con, $su);
          //  $x = UPClass2::resetProdSched($con, $refid, $inclwip);
        }
        // get the run order information
        // $s = "Select * from MILRUNP where Milrun = $mr";
        // $r = db2_exec($con, $s);
        // $row = db2_fetch_assoc($r);
    
        $rev = 1;  // Default Revision Number
        $id = UPClass2::getNextProdId($con);   // Get the New ID number
        //  If the start day is blank get the next production date/time to append this entry at the end of the scedule
      //  if (trim($sday) == '') {
      //      $dt = UPClass::getNextProdTime($con);
      //      $day = $dt['DAY'];
      //      $stime = $dt['TIME'];
     //   } else     $day = $sday;                                                
           
    
    
            // Insert the production schedule
            $s = "Insert into prod_schedule values(
            $rev,
            $run,
            '$grd',
            '$info',
            '$notes',
            0,
            0,
            0,
            $ehours,
            ' ',
            '$sts',
            Date(STRTS),
            'Open',
            ' ',
            Time(STRTS),
            $id,
            current timestamp,
            '$sts',
            '$ets',
            $refid
            )  with NC";
    
            $r = db2_exec($con, $s);
            $x['qryInsert'] = $s;
            $x['qryInsertMsg'] = db2_stmt_errormsg();
            // // // // // // var_dump($s, db2_stmt_errormsg());
    
            // We default the schedule start and end time stamps  - then update them next
    
            $su = "Update Prod_Schedule set
            SCHTS = TIMESTAMP(SCHDATE, SCHTIME),
            SCHETS = TIMESTAMP(SCHDATE, SCHTIME) + (ESTHOURS * 60) MINUTES
            where PROD_ID = $id with NC";
            $ru = db2_exec($con, $su);
            $x['ID'] = $id;
            
            //TIMESTAMPDIFF(4, CHAr(time_end - time_start) ))
            // add the schedule time entry
            $line = 1;
            $type = 'Sched';
            $stat = 'Open';
            $s2 = "Insert into Prod_Sched_Time values(
            $id,
            $line,
            '$type',
            '$sts',
            '$ets',
            TIMESTAMPDIFF(4, CHAr(time_end - time_start) )),
            '$note',
            '$stat',
            $ref
            
            )";
            $r2 = db2_exec($con, $s2);
            
            
            
            
            $inclwip = true;
            UPClass2::setSchedule($con);
            return $x;
    }   
//**************************************************************************************************
    public static function resetProdSched($con, $id, $inclwip)
    {
        
       // Ignore status codes of ...... 
        $inclwip = true;
        $planned = '';
        if ($inclwip) {
            $list = "schedstat not in ('Closed','Complete')";
        } else {
            $list = "schedstat not in ('Closed','Complete', 'WIP')";
        }
        // Ignore the exceptions at this time and adjust all entries 
       $s = "select * from prod_schedule where schedstat = 'Open' and milrun > 100 ";

        
        // The passed ID number is ignored and the schedule is reset based on the first open id.
        $s = "Select * from Prod_Schedule where PROD_ID = $id ";
        $r = db2_exec($con, $s);
     // // // // var_dump($s, db2_stmt_errormsg($r));  
        $row = db2_fetch_assoc($r);
        $rowid = $row;
        $refid = $row['REFRUN'];
        // if the reference ID is not zero - then reset to the reference number;
        if ($refid !== 0 && trim($refid) !== ''){
            $s = "Select * from Prod_Schedule where PROD_ID = $refid ";
            $r = db2_exec($con, $s);
            $rowid = db2_fetch_assoc($r);
        }
        
      //  $id = $row['PROD_ID'];
        $startts = $rowid['SCHTS'];
        // get the current record
    //    $s = "Select * from prod_schedule where Prod_id = $id";
    //    $r = db2_exec($con, $s);
        
   //     $rowid = db2_fetch_assoc($r);
        $curmr = $rowid['MILRUN'];
        $refrun = $rowid['REFRUN'];
        // if ($refrun !== 0){
        // $id = $refrun;
        // $s = "Select * from prod_schedule where Prod_id = $id";
        // $r = db2_exec($con, $s);
        // $rowid = db2_fetch_assoc($r);
        // $curmr = $rowid['MILRUN'];
        // $refrun = $rowid['REFRUN'];
        // }
        
        // $ts = $nday = date('Y-m-d-H.i.s.000000', date());
        // get the end time stamp and ad a min.
        $s = "select schets + 1 minute AS NETS   from prod_schedule  where Prod_id = $id";
        $r = db2_exec($con, $s);
        // // // // var_dump($s, db2_stmt_errormsg());
        $row = db2_fetch_assoc($r);
        $ets = strtotime($row['NETS']);
        $lastid = $id;
        $x['NextEndTsQry'] = $s;
        $x['NextEndTsQryMsg'] = db2_stmt_errormsg();
        // // // // // // var_dump($s, db2_stmt_errormsg(), $lastid);
        
        // check for the next planned downtime unless the change is to the the downtime
        // if ($curmr !== 100){

            // } else $planned = '';
            // 2017-03-09-05.51.09.867918
      //  $inclwip = true;
      //  $planned = '';
      
        $s = "select * from prod_schedule where 
         SCHEDSTAT is in  ('Open', 'BadRun')  and MILRUN > 100 and PROD_ID <> $id";
        
        $r = db2_exec($con, $s);
        $x['NextGetCurQry'] = $s;
        $x['NextGetCurQryMsg'] = db2_stmt_errormsg();
    // // var_dump($s, db2_stmt_errormsg());
        while ($row = db2_fetch_assoc($r)) {
            
            $thisid = $row['PROD_ID'];
            
            $s1 = "update  prod_schedule set schts  =                      
(Select schets + 1 minute                               
from prod_schedule where Prod_id = $lastid)                 
WHERE PROD_ID = $thisid    with NC ";
            $r1 = db2_exec($con, $s1);
              // var_dump('624: ' , $s1, db2_stmt_errormsg(), $thisid, $lastid);
            $s1 = "update prod_schedule set SCHDATE = DATE(SCHTS), SCHTIME =           
TIME(SCHTS), SCHETS = SCHTS + (ESTHOURS * 60) MINUTES                            
    WHERE PROD_ID = $thisid    with NC ";
            $r1 = db2_exec($con, $s1);
            $x['UpdateQry'] = $s1;
            $x['UpdateQtyMsg'] = db2_stmt_errormsg();
         //   // // var_dump($s1, db2_stmt_errormsg());
            // // // // // // var_dump($s1, db2_stmt_errormsg());
            $lastid = $thisid;
        }
        return $x;
    }
    public static function setProdLog($con, $usr, $app){
        $s = "Insert into Prod_Log values(
        '$app',
        '$usr',
        current timestamp) with NC";
        $r = db2_exec($con, $s);
    }
    
    public static function getProdWip($con){
        $s = "select value from prod_reel where ID is not null
and Variable = 'Mill Run number' and value <> ' '
order by ID desc fetch first 1 row only          ";
        $r = db2_exec($con, $s);
     //   var_dump($s, db2_stmt_errormsg());
        $wiprunrow = db2_fetch_assoc($r);
        $wiprun = $wiprunrow['VALUE'];
        
     $s = "Update Prod_Schedule set SchedStat = 'WIP' where milrun = $wiprun with NC";
     $r = db2_exec($con, $s);
    // var_dump($s, db2_stmt_errormsg());
        return $wiprun;
    }
    
    public static function getProdWipStart($con, $wiprun){
        $s = "select entryon from prod_reel where value = '$wiprun'
        and Variable = 'Mill Run number'
        order by ID desc fetch first 1 row only  ";
        $r = db2_exec($con, $s);
        $wiprunrow = db2_fetch_assoc($r);
        $wipstart = $wiprunrow['ENTRYON'];
        // entry on is returned yyyy-mm-dd hh:mm:ss format need to change to iso
        $wipstart = str_replace(' ', '-', trim($wipstart));
        $wipstart = str_replace(':', '.', trim($wipstart));
        return $wipstart;
    }
    public static function ClearSchedWip($con){
        $s = "Update Prod_Schedule set schedstat = 'Open' where schedstat = 'WIP' with NC";
        $r = db2_exec($con, $s);
        
    }
    
    public static function getWipProdid($con, $wiprun){
        $s = "Select *  from Prod_Schedule where milrun = $wiprun";
        $r = db2_exec($con, $s);
        $row = db2_fetch_assoc($r);
      
        return $row;
    }
    public static function getSchedByProdid($con, $prodid){
        $s = "Select *  from Prod_Schedule where Prod_id = $prodid";
        $r = db2_exec($con, $s);
        $row = db2_fetch_assoc($r);
        
        return $row;
    }
    public static function InsertProdSched ($con, $milrun,$grade,$info, $notes, $esthours, $startdate, $stime, $timestamp){
        
        $smax = "Select max(Prod_ID) as MAX from Prod_Schedule";
        $rmax = db2_exec($con, $smax);
        $rowmax = db2_fetch_assoc($rmax);
        $nextid = $rowmax['MAX'] + 1;
        $snew = "Insert into Prod_schedule values(
        0,
        $milrun,
        '$grade',
        '$info',
        '$notes',
        0,
        0,
        0,
        $esthours,
        ' ',
        '2001-01-01-00.00.01.000000',
        '$startdate',
        'Open',
        ' ',
        '$stime',
        $nextid,
        current timestamp,
        timestamp('$timestamp'),
        timestamp('$timestamp') + $esthours hours,
        0) with NC";
        $rnew = db2_exec($con, $snew);
        //var_dump($snew, db2_stmt_errormsg());
        $prodid = $nextid;
        
        $s4 = "Select * from Prod_Schedule where Prod_id = $prodid";
        $r4 = db2_exec($con, $s4);
        $row4 = db2_fetch_assoc($r4);
        $etime = $row4['SCHETS'];
     //   echo "<br>Prod ID: " .  $row4['PROD_ID'] . " Mill Run: " . $row4['MILRUN'] . " Start Time/stamp: " . $row4['SCHTS'] . " End: " . $row4['SCHETS'] . '<br>';
        $s = "select * from Prod_Schedule where (schts >= '$timestamp' or schts is null)' and schedstat <> 'Complete' and milrun > 1000 order by SCHTS";  
        UPClass2::resetProdSchedule($con, $s, $etime);
        
    }
    
    public static function RemoveProdSched ($con, $prodid){

        $s4 = "Select * from Prod_Schedule where Prod_id = $prodid";
        $r4 = db2_exec($con, $s4);
        $row4 = db2_fetch_assoc($r4);
        $etime = $row4['SCHTS'];
        $timestamp = $row4['SCHTS'];
        $milrun = $row4['MILRUN'];
        if ($milrun < 1000){
            $sdel = "Delete from Prod_Schedule where Prod_ID = $prodid with NC";
            
        } else {
     //  echo "<br>Prod ID: " .  $row4['PROD_ID'] . " Mill Run: " . $row4['MILRUN'] . " Start Time/stamp: " . $row4['SCHTS'] . " End: " . $row4['SCHETS'] . '<br>';
        $sdel = "Update Prod_Schedule set SchedStat = 'Closed' where Prod_ID = $prodid with NC";
        }
        $rdel = db2_exec($con, $sdel);
      //  var_dump($sdel, db2_stmt_errormsg());
        $s = "select * from Prod_Schedule where (schts >= '$timestamp' or schts is null) and schedstat <> 'Complete' and milrun > 1000 order by SCHTS";
        
        UPClass2::resetProdSchedule($con, $s, $etime);
        
    }
    public static function UpdateProdSched ($con, $prodid, $timestamp, $info, $notes ){
        $x = array();
        $xi = 0;
        $s4 = "Select * from Prod_Schedule where Prod_id = $prodid";
        $r4 = db2_exec($con, $s4);
        $row4 = db2_fetch_assoc($r4);
        $etime = $timestamp;
        $savtimestamp = $row4['SCHTS'];
        //    $timestamp = $etime;
        $x['Info'][$xi]['Step'] = 'Update Originial';
        $x['Info'][$xi]['ProdId'] = $row4['PROD_ID'];
        $x['Info'][$xi]['MilRun'] = $row4['MILRUN'];
        $x['Info'][$xi]['StartDT'] = $row4['SCHTS'];
        $x['Info'][$xi]['EndDT'] = $row4['SCHETS'];
        $x['Info'][$xi]['EstHours'] = $row4['ESTHOURS'];
        $x['Info'][$xi]['Info'] = $row4['ADLINFO'];
        $x['Info'][$xi]['Notes'] = $row4['ADLNOTES'];
        
        $newdur = 0;
        $ts1 = $row['SCHTS'];
        $ts2 = $row['SCHETS'];
        $dur = $row4['ESTHOURS'];
        $s2 = "Update Prod_Schedule set SCHTS = timestamp('$timestamp') ,  LCDT = current TIMESTAMP,
        ADLINFO = '$info', ADLNOTES = '$notes'
        where Prod_ID = $prodid with NC";
        $r2 = db2_exec($con, $s2);
  
        $s3 = "Update Prod_Schedule set schets = schts + esthours hours,
        SCHDate=date(SCHTS),
        SCHTIME=time(SCHTS)
        where Prod_ID = $prodid with NC";
        $r3 = db2_exec($con, $s3);
     //   echo "<br>Prod ID: " . $prodid;
        // get the endtime of this schedule id
        $s4 = "Select * from Prod_Schedule where Prod_id = $prodid";
        $r4 = db2_exec($con, $s4);
        $row4 = db2_fetch_assoc($r4);
        $etime  = $row4['SCHETS'];
        
        $xi++;
        $x['Info'][$xi]['Step'] = 'Update New';
        $x['Info'][$xi]['ProdId'] = $row4['PROD_ID'];
        $x['Info'][$xi]['MilRun'] = $row4['MILRUN'];
        $x['Info'][$xi]['StartDT'] = $row4['SCHTS'];
        $x['Info'][$xi]['EndDT'] = $row4['SCHETS'];
        $x['Info'][$xi]['EstHours'] = $row4['ESTHOURS'];
        $x['Info'][$xi]['Info'] = $row4['ADLINFO'];
        $x['Info'][$xi]['Notes'] = $row4['ADLNOTES'];
        
        $s = "select * from Prod_Schedule where  schedstat <> 'Complete' and prod_id <> $prodid  and (schts >= '$savtimestamp' or schts is null) and milrun > 1000 order by SCHTS";
        UPClass2::resetProdSchedule($con, $s, $etime);
        
    }
    public static function UpdateProdSchedCT ($con, $prodid, $chgHrs, $estHours ){
        $x = array();
        $xi = 0;
        $s4 = "Select * from Prod_Schedule where Prod_id = $prodid";
        $r4 = db2_exec($con, $s4);
      //  var_dump($s4, db2_stmt_errormsg());
        $row4 = db2_fetch_assoc($r4);
        //$etime = $timestamp;
        $savtimestamp = $row4['SCHTS'];
        //    $timestamp = $etime;
        $x['Info'][$xi]['Step'] = 'Update Originial';
        $x['Info'][$xi]['ProdId'] = $row4['PROD_ID'];
        $x['Info'][$xi]['MilRun'] = $row4['MILRUN'];
        $x['Info'][$xi]['StartDT'] = $row4['SCHTS'];
        $x['Info'][$xi]['EndDT'] = $row4['SCHETS'];
        $x['Info'][$xi]['EstHours'] = $row4['ESTHOURS'];
        $x['Info'][$xi]['Info'] = $row4['ADLINFO'];
        $x['Info'][$xi]['Notes'] = $row4['ADLNOTES'];
        
        $newdur = 0;
        $ts1 = $row4['SCHTS'];
        $ts2 = $row4['SCHETS'];
        $dur = $row4['ESTHOURS'];
        $s2 = "Update Prod_Schedule set REV = REV+1, ESTHOURS = $estHours,SCHETS = SCHTS + (ESTHOURS * 60) MINUTES  where PROD_ID = $prodid  with NC";
        $r2 = db2_exec($con, $s2);
        var_dump($s2, db2_stmt_errormsg());
        $s3 = "Update Prod_Schedule set schets = schts + esthours hours,
        SCHDate=date(SCHTS),
        SCHTIME=time(SCHTS)
        where Prod_ID = $prodid with NC";
        $r3 = db2_exec($con, $s3);
        //   echo "<br>Prod ID: " . $prodid;
        // get the endtime of this schedule id
        $s4 = "Select * from Prod_Schedule where Prod_id = $prodid";
        $r4 = db2_exec($con, $s4);
        var_dump($s4, db2_stmt_errormsg());
        $row4 = db2_fetch_assoc($r4);
        $etime  = $row4['SCHETS'];
        
        $xi++;
        $x['Info'][$xi]['Step'] = 'Update New';
        $x['Info'][$xi]['ProdId'] = $row4['PROD_ID'];
        $x['Info'][$xi]['MilRun'] = $row4['MILRUN'];
        $x['Info'][$xi]['StartDT'] = $row4['SCHTS'];
        $x['Info'][$xi]['EndDT'] = $row4['SCHETS'];
        $x['Info'][$xi]['EstHours'] = $row4['ESTHOURS'];
        $x['Info'][$xi]['Info'] = $row4['ADLINFO'];
        $x['Info'][$xi]['Notes'] = $row4['ADLNOTES'];
        
        $s = "select * from Prod_Schedule where  schedstat <> 'Complete' and prod_id <> $prodid  and (schts >= '$savtimestamp' or schts is null) and milrun > 1000 order by SCHTS";
        $s = "select * from Prod_Schedule where  schedstat <> 'Complete' and prod_id <> $prodid  and (schts >= '$savtimestamp' or schts is null) and milrun > 1000 order by SCHTS";
        UPClass2::resetProdSchedule($con, $s, $etime);
        
    }
    public static function UpdateProdSchedDD ($con, $prodid, $chgHrs ){
        $x = array();
        $xi = 0;
        $s4 = "Select * from Prod_Schedule where Prod_id = $prodid";
        $r4 = db2_exec($con, $s4);
        $row4 = db2_fetch_assoc($r4);
        $etime = $timestamp;
        $savtimestamp = $row4['SCHTS'];
        //    $timestamp = $etime;
        $x['Info'][$xi]['Step'] = 'Update Originial';
        $x['Info'][$xi]['ProdId'] = $row4['PROD_ID'];
        $x['Info'][$xi]['MilRun'] = $row4['MILRUN'];
        $x['Info'][$xi]['StartDT'] = $row4['SCHTS'];
        $x['Info'][$xi]['EndDT'] = $row4['SCHETS'];
        $x['Info'][$xi]['EstHours'] = $row4['ESTHOURS'];
        $x['Info'][$xi]['Info'] = $row4['ADLINFO'];
        $x['Info'][$xi]['Notes'] = $row4['ADLNOTES'];
        
        $newdur = 0;
        $ts1 = $row['SCHTS'];
        $ts2 = $row['SCHETS'];
        $dur = $row4['ESTHOURS'];
        $s2 = "Update Prod_Schedule set REV = REV+1, SCHTS = SCHTS + ($chgHrs * 60) MINUTES    where PROD_ID = $prodid with NC";
        $r2 = db2_exec($con, $s2);
        
        $s3 = "Update Prod_Schedule set schets = schts + esthours hours,
        SCHDate=date(SCHTS),
        SCHTIME=time(SCHTS)
        where Prod_ID = $prodid with NC";
        $r3 = db2_exec($con, $s3);
        //   echo "<br>Prod ID: " . $prodid;
        // get the endtime of this schedule id
        $s4 = "Select * from Prod_Schedule where Prod_id = $prodid";
        $r4 = db2_exec($con, $s4);
        $row4 = db2_fetch_assoc($r4);
        $etime  = $row4['SCHETS'];
        
        $xi++;
        $x['Info'][$xi]['Step'] = 'Update New';
        $x['Info'][$xi]['ProdId'] = $row4['PROD_ID'];
        $x['Info'][$xi]['MilRun'] = $row4['MILRUN'];
        $x['Info'][$xi]['StartDT'] = $row4['SCHTS'];
        $x['Info'][$xi]['EndDT'] = $row4['SCHETS'];
        $x['Info'][$xi]['EstHours'] = $row4['ESTHOURS'];
        $x['Info'][$xi]['Info'] = $row4['ADLINFO'];
        $x['Info'][$xi]['Notes'] = $row4['ADLNOTES'];
        
        $s = "select * from Prod_Schedule where  schedstat <> 'Complete' and prod_id <> $prodid  and (schts >= '$savtimestamp' or schts is null) and milrun > 1000 order by SCHTS";
        UPClass2::resetProdSchedule($con, $s, $etime);
        
    }
    
    
    public static function resetProdSchedule($con, $s, $etime){
        
        //The sql statment varies depending on update/delete or insert see those functions
        
        $r = db2_exec($con, $s);
        
        
        while ($row = db2_fetch_assoc($r)){
            $prodid = $row['PROD_ID'] ;
        //    echo "<br>Prod ID: " .  $row['PROD_ID'] . " Mill Run: " . $row['MILRUN'] . " Start Time/stamp: " . $row['SCHTS'] . " End: " . $row['SCHETS'];
            $newdur = 0;
            $ts1 = $row['SCHTS'];
            $ts2 = $row['SCHETS'];
            // $prodid = $row['PROD_ID'];
            $dur = $row['ESTHOURS'];
            $s2 = "Update Prod_Schedule set SCHTS = timestamp('$etime') + 1 minute,  LCDT = current TIMESTAMP
            where Prod_ID = $prodid with NC";
            $r2 = db2_exec($con, $s2);
            
            //  echo "<br>Prod ID: " . $prodid;
            //  var_dump($s2, db2_stmt_errormsg());
            $s3 = "Update Prod_Schedule set schets = schts + esthours hours,
            SCHDate=date(SCHTS),
            SCHTIME=time(SCHTS)
            where Prod_ID = $prodid with NC";
            $r3 = db2_exec($con, $s3);
       //    echo "<br>Prod ID: " . $prodid;
            // get the endtime of this schedule id
            $s4 = "Select * from Prod_Schedule where Prod_id = $prodid";
            $r4 = db2_exec($con, $s4);
            $row4 = db2_fetch_assoc($r4);
            $etime = $row4['SCHETS'];
          //  echo "<br>Prod ID: " .  $row4['PROD_ID'] . " Mill Run: " . $row4['MILRUN'] . " Start Time/stamp: " . $row4['SCHTS'] . " End: " . $row4['SCHETS'] . '<br>';
            
        }
        
        
    }
    
    public static function setSchedule($con){
       // require_once '../uphead.php';
        //// var_dump($con);
        
        $s = "update prod_schedule f4 set RUNSTAT = (
select min(
 cast(('20' || Ryear || '-' || Rmonth || '-' || rday)
 as date )) as ReqDate from milposp f1
 left join ordentp f2 on f2.milord = f1.milord
 left join ordatep f3 on f3.milord = f1.milord and f3.ship# =
f1.ship#
 where milrn# = f4.milrun
            
)                                                            ";
        $r = db2_exec($con, $s);
        
        

        
        
        
        
        $s = "Select * from Prod_Schedule where SchedSTAT not in('complete', 'Closed','closed','Complete', 'Finished') Order by SCHEDSTAT DESC, SHIFT, RUNSTAT , SUBSTR(GRD8, 1,2) || SUBSTR(GRD8, 11,2), SCHTS";
        $r= db2_exec($con, $s);
        // var_dump($s, db2_stmt_errormsg());
        $schts = '';
        $savts = '';
        while ($row = db2_fetch_assoc($r)){
            $schts = $row['SCHTS'];
            $id = $row['PROD_ID'];
   //         echo "<br> $id / $savts";
            // Wip assume start time is corect and set the end time to start time + estimated hours
            if (trim($row['SCHEDSTAT']) == 'WIP'){
                $id = $row['PROD_ID'];
                
                $s2 = "Select TIMESTAMPDIFF(4, CHAr((Current timestamp - (ESTHOURS * 60)
MINUTES) -
(SCHETS + (ESTHOURS * 60) MINUTES)
) ) as delay,  schts, ESTHOURS
from Prod_Schedule where Prod_ID = $id              "  ;
      $r2 = db2_exec($con, $s2);
      $row2 = db2_fetch_assoc($r2);
      $delay = $row2['DELAY'];
      if ((int)$delay > 0){
          $esthours = $row2['ESTHOURS'] + ((float)$delay / 60);
          $s3 = "Update Prod_Schedule  set ESTHOURS = $esthours where Prod_ID = $id WITH NC";
        //  $r3 = db2_exec($con, $s3);
        //  var_dump($s3, db2_stmt_errormsg());
      }
                
                
                
                $su = "Update Prod_Schedule set
                SCHTS = TIMESTAMP(SCHDATE, SCHTIME),
                SCHETS = TIMESTAMP(SCHDATE, SCHTIME) + (ESTHOURS * 60) MINUTES
                where PROD_ID = $id with NC";
                $ru = db2_exec($con, $su);
                // var_dump($su, db2_stmt_errormsg());
            } else {
                $sb = "Update Prod_Schedule set SCHTS = '$savts'  where Prod_id = $id with NC";
                $rb = db2_exec($con, $sb);
                // var_dump($sb, db2_stmt_errormsg());
                $sc = "Update  Prod_Schedule set schdate = date(SCHTS), schtime = time(SCHTS),
                SCHETS = SCHTS + (ESTHOURS * 60) MINUTES WHERE PROD_ID = $id WITH nc";
                $rc = db2_exec($con, $sc);
                // var_dump($sc, db2_stmt_errormsg());
            }
            // lets get the new end time  which will be the start time for the next order;
            $sa = "Select schets from Prod_schedule where prod_id = $id";
            $ra = db2_exec($con, $sa);
            $rowa = db2_fetch_assoc($ra);
            $savts = $rowa['SCHETS'];
            
            
            
        }
    }
    // Time per pound to produce by grade
   public static function getTPP($con, $grd){
       $s6 = "select f_gettpp(grd8) as TPP from prod_schedule where grd8 =
       '$grd' fetch first 1 row only                 ";
       $r6 = db2_exec($con, $s6);
       // var_dump($s6, db2_stmt_errormsg());
       $row6 = db2_fetch_assoc($r6);
       $tpp = $row6['TPP'];
       //   echo "<br>" .  $row6['PTIME'] . '/' . $row6['CBR'];
       //   var_dump($row6);
       if ((float)$tpp <= 0) $tpp = .001;
       
       return $tpp;
   }
   // average weight per roll by grade
   public static function getAWPR ($con, $grd){
       $sa = "select  f_avrwgtg(grd8) as avrwgt from lpmast
       where grd8  = '$grd' fetch first 1 row only";
       $ra = db2_exec($con, $sa);
       $rowa = db2_fetch_assoc($ra);
       $avrwgt = $rowa['AVRWGT'];
       return $avrwgt;
   }
   
   // estimated time for suplied number of rolls by grade
   public static function  getESTTIME($con, $grd, $rolls){
      // require ('UPClass2.php');
       $avrwgt = UPClass2::getAWPR($con, $grd);
       $tpp = UPClass2::getTPP($con, $grd);
       
       $etime = (($rolls * $avrwgt) * $tpp)/60 ;
       return $etime;
       
   }
   public static function getUserData($con){
       $user = strtoupper($_SERVER['PHP_AUTH_USER']);
       $s  = "Select * from syuser where USUSER = '$user'";
       $r = db2_exec($con, $s);
       $row = db2_fetch_assoc($r);
       
       $usrName = $row['USDESC'];
       
    // name not found - lets copy 
       if (trim($usrName) == ''){
           $s2 = "Select * from syuser where ususer = 'ANEDEAU'";
    
           $r2 = db2_exec($con, $s2);
           $row['USUSER'] = $user;
           $row['USDESC'] = 'yourname';
           $row['USEMAL'] = 'youremail@uppaperllc.com';
           $s3 = "Insert into SYUSER value(";
           $s3.= "'" . $row['USUSER'] . "',";
           $s3.= "'" . $row['USROLE'] . "',";
           $s3.= "'" . $row['USDESC'] . "',";
           $s3.= $row['USCUST'] . ",";
           $s3.= $row['USVEND'] . ",";
           $s3.= $row['USSLSM'] . ",";
           $s3.= "'" . $row['USCLCK'] . "',";
           $s3.= "'" . $row['USCATN'] . "',";
           $s3.= "'" . $row['USNEWS'] . "',";
           $s3.= "'" . $row['USEMAL'] . "',";
           $s3.= "'" . $row['USFAX'] . "',";
           $s3.= "'" . $row['USADMN'] . "',";
           $s3.= "'" . $row['USASSI'] . "',";
           $s3.= "'" . $row['USADOC'] . "',";
           $s3.= "'" . $row['USMEDM'] . "',";
           $s3.= $row['USDPLT'] . ",";
           $s3.= "'" . $row['USDEPT'] . "',";
           $s3.= "'" . $row['USWC'] . "',";
           $s3.= "'" . $row['USDESCU'] . "',";
           $s3.= "CURRENT TIMESTAMP,";
           $s3.= "'" . $user . "',";
           $s3.= "'" . $row['USTSPT'] . "";
           $s3.=') with NC;';
           $r3 = db2_exec($con, $s);
           
       }
       
       $x['USER'] = $user;
       $x['NAME'] = $row['USDESC'];
       $x['EMAIL'] = $row['USEMAL'];
       $x['SL'] = (int)substr($row['USFAX'],0,3);
       
       return $x;
       
   
   }
   public static function getUserDefault($con){
       $user = strtoupper($_SERVER['PHP_AUTH_USER']);
       
       
   }
   
   public static function checkMenuOptions($con){

       $user = strtoupper($_SERVER['PHP_AUTH_USER']);
       $s = "select count(*) as CNT from dailyprodopt f1                   
 join dailyprod f2 on f1.pgmid = f2.pgmid and UsrID = '$user' ";
       $r = db2_exec($con, $s);
       $row = db2_fetch_assoc($r);
       if ($row['CNT'] == '0'){
           $s = "Select * from DailyProd ";
           $r = db2_exec($con, $s);
           while ($row = db2_fetch_assoc($r)){
               $pid = (float)$row['PGMID'];
               $s2 = "Insert into DailyProdOpt values(
$pid,
'$user',
'N',' '
) with NC";
               db2_exec($con, $s2);
          //     var_dump($s2, db2_stmt_errormsg());
               
           }
  
           
           
       }
      
       
       $x = UPClass2::getUserData($con);
       $sl = $x['SL'];
      // var_dump($sl, $x);
       // Generic all users functions;
       $menu = '';
       if ($sl == 500) { 
       $menu .= '<li><a href="Prod_Cal.php" target="ProdCal">Production Calendar</a>';
       $menu .= '<li><a href="getOrderStatus.php" target="OrdStat">Order Status Review</a>';
       $menu .= '<li><a href="DailyProd.php" target="DailyRpt">Daily Production Review</a>';
       $menu .= '<li><a href="FPMbyBW.php" target="FPM">Feet/Min Review</a>';
       $menu .= '<li><a href="ActiveCustomers.php" target="ActiveCust">Active Customers</a>';
       $menu .= '<li><a href="Toursheetlist.php" target="Toursheet">TourSheet</a>';
       
       $menu .= '<li><a href="OrderUp.html" target="OrdDetail">Show Order Detail by Order Number</a>';
       }
       
       if ($sl  == 700){  // Supervisor level
           $menu .= '<li><a href="EmpShift.php" target="EmpShift">Shift Maintenance</a>';
           $menu .= '<ul><li><a href="aProdSched.php" target="ProdSched">Production Schedule Maintenace</a>';
       }
       if ($sl  ==  800){  // managers level
           
       }
       
       if ($sl  == 900){  // super user
           $menu .= '<li><a href="http://10.6.1.11/Intranet/UpPaper/Prod_Load/app.html" target="EmpShift">ManualReload</a>';
           $menu .= '<li><a href="http://10.6.1.11:2001/HTTPAdmin" target="EmpShift">HTTP Server Menu</a>';
           http://10.6.1.11/Intranet/UpPaper/Prod_Load/app.html
       }
       $menu .= '</ul>';    
       
       return $menu;
   }
    
   
   public static function ProdReelLoad ($con, $dbh,$inreel){
       
     //  include "../../msConnect.php";
     //  include "../../UPHead.php";
      
       $sl = "SELECT distinct substring(rollid,1,5) as REEL FROM lpmast
       where rollid like '$inreel%'";
       $rl = db2_exec($con, $sl);
       $c= 0;
       $data = array();
       
       while ($rowl = db2_fetch_assoc($rl)){
           $reel = $rowl['REEL'];
           
           //echo "<br>*** Processing Reel $reel ***";
           
           $s1 = "Delete  from PROD_REEL where EventName = '$reel' WITH NC";
           $r1 =  db2_exec($con, $s1);
           
           if (!$r1) {
               var_dump($r1, $s1, db2_stmt_errormsg());
           }
           
           $s3 = "select * from SDK_V_PAVariableResultEvent_UP where EventName  Like '$reel%' ";
           
           
           $d=0;
           $r3 = $dbh->query($s3);
           if (!$r3){
               $success = false;
               $failure = true;
               $data['Fail_Response'] = $r3;
               $data['Failed_SQL'] = $s3;
               $data['Failed_Error_Msg'] = $dbh->errorInfo();
           }
           
           
           while ($row3 = $r3->fetch(PDO::FETCH_NUM)) {
               //var_dump($row3);
               if (trim($row3[19]) == '') $row3[19] = 0;
               if (trim($row3[20]) == '') $row3[20] = 0;
               if (trim($row3[21]) == '') $row3[21] = 0;
               if (trim($row3[22]) == '') $row3[22] = 0;
               if (trim($row3[23]) == '') $row3[23] = 0;
               if (trim($row3[24]) == '') $row3[24] = 0;
               if (trim($row3[25]) == '') $row3[25] = 0;
               if (trim($row3[27]) == '') $row3[27] = 0;
               if (trim($row3[28]) == '') $row3[28] = 0;
               if (trim($row3[29]) == '') $row3[29] = 0;
               if (trim($row3[31]) == '') $row3[31] = 0;
               if (trim($row3[32]) == '') $row3[32] = 0;
               if (trim($row3[37]) == '') $row3[37] = 0;
               if (trim($row3[38]) == '') $row3[38] = 0;
               if (trim($row3[40]) == '') $row3[40] = 0;
               if (trim($row3[42]) == '') $row3[42] = 0;
               if (trim($row3[44]) == '') $row3[44] = 0;
               $etime = strtotime($row3[39]);
               $newetime = date('Y-m-d H:i:s',$etime);
               // var_dump($newetime);
               // echo "<br>";
               
               $s = "Insert into fmanstq/PROD_REEL values(
 	".$row3[0].",
 	".$row3[1].",
 	'".$row3[2]."',
 	'".$row3[3]."',
 	'".$row3[4]."',
 	'".$row3[5]."',
 	'".$row3[6]."',
 	'".$row3[7]."',
 	'".$row3[8]."',
 	'".$row3[9]."',
 	'".$row3[10]."',
 	'".$row3[11]."',
 	'".$row3[12]."',
 	'".$row3[13]."',
 	'".$row3[14]."',
 	'".$row3[15]."',
 	'".$row3[16]."',
 	'".$row3[17]."',
 	'".$row3[18]."',
 	".$row3[19].",
 	".$row3[20].",
 	".$row3[21].",
 	".$row3[22].",
 	".$row3[23].",
 	".$row3[24].",
 	".$row3[25].",
 	'".$row3[26]."',
 	".$row3[27].",
 	".$row3[28].",
 	".$row3[29].",
 	'".$row3[30]."',
 	".$row3[31].",
 	".$row3[32].",
 	'".$row3[33]."',
 	'".$row3[34]."',
 	'".$row3[35]."',
 	'".$row3[36]."',
 	".$row3[37].",
 	".$row3[38].",
 	'".$newetime."',
 	".$row3[40].",
 	'".$row3[41]."',
 	".$row3[42].",
 	'".$row3[43]."',
 	".$row3[44]."
 	)";
               $r = db2_exec($con, $s);
               $x['REEL'] = $row3[5];
               if (!$r){
                   $x['Stat'] = "Insert_Failed";
                   $x['Fail_Msg']= db2_stmt_errormsg();
               } else {
                   //echo "<br>Reel: " .	$row3[5] . ' Test: ' . $row3[24] . ' ' . db2_stmt_error();
                   $x['Stat'] = "Created";
                   $x['Reel'] = $row3[5];
                   
               }
               $data['root'][$c]  = $x;
               $c += 1;
           }
       }
       
       
       $spr = "select distinct substr(rollid,1,5) as ROLLID           from lpmast f1
where not exists (select * from prod_reel where eventname =
substr(rollid,1,5)) and rollid <> ' '                        ";
       $rpr = db2_exec($con, $spr);
       while ($rowpr = db2_fetch_assoc($rpr)){
           $rollid = $rowpr['ROLLID'];
           $s = "INSERT INTO FMANSTQ/PROD_REEL (EVENTNAME) VALUES('$rollid')";
           $r = db2_exec($con, $s);
       }
       
       
       
       
   }
   
   public static function getProdDownTime($con, $dbh){
       
       
       $s = "select top 1000  * from SDK_V_PADowntimeEvent where StartTime  >=  '2017-09-01'";
       try{
           $r = $dbh->query($s);
       } catch (PDOException $e) {
           // echo 'Connection failed: ' . $e->getMessage();
       }
       //Echo "Success ?<br> ";
       //var_dump($r);
        echo "<table border = '1'>";
        echo "<tr>
       
       <th>4 Start time</th>
       <th>5 End Time</th>
       <th>6 Fault</th>
       <th>7 Cause 1</th>
       <th>8 Cause 2</th>
       <th>9 Cause 3</th>
       <th>10 Cause 4</th>
       
       <th> 19 Source Prod Line </th>
       <th> 20 Prod Unit </th>
       <th> 40 Duration </th>
       
       ";
       while ($row3 = $r->fetch(PDO::FETCH_NUM)) {
           // var_dump($row3);
            echo '<tr>';
           
            echo "<td>". $row3[4] . "</td>";
            echo "<td>". $row3[5] . "</td>";
            echo "<td>". $row3[6] . "</td>";
            echo "<td>". $row3[7] . "</td>";
            echo "<td>". $row3[8] . "</td>";
            echo "<td>". $row3[9] . "</td>";
            echo "<td>". $row3[10] . "</td>";
           
           
            echo "<td>". $row3[19] . "</td>";
            echo "<td>". $row3[20] . "</td>";
           
           
           // echo "<td>". $row3[40] . "</td>";
           
           
           
           
           
           $s1 = "Select Max(SHEETID) as SID  from FMANSTQ.Toursheet_time";
           $r1 = db2_exec($con, $s1);
           //   var_dump($s1, db2_stmt_errormsg());
           //   echo '<br>';
           $row1 = db2_fetch_assoc($r1);
           $sheetid = $row1['SID'] + 1;
           
           $timecode= 'DownTime';
           $eventts = strtotime($row3[4]);
           // var_dump($eventts);
           $edate =   date( "Y-m-d", $eventts);
           $etime =   date("H:i:s", $eventts);
           $edate  = substr($row3[4],0,10);
           $etime = substr($row3[4], 11,8);
           $edur  = floatval($row3[40]);
           if (trim($row3[7]) == '') {
               $timeCat = 'Sched';
               $timeid = 70;
           } else {
               $timeCat = 'UnSched';
               $timeid = 80;
           }
           $comment = trim($row3[7]) . ' -  ' . trim($row3[8]) . ' - ' .  trim($row3[9]) . ' - ' . trim($row3[10]) ;
           $rcnt = 0;
           $s5 = "Select count(*) as RCNT from Fmanstq/Toursheet_time where eventdate = '$edate' and eventtime = '$etime' and timecode = '$timecode' and timeID = $timeid";
           $r5 = db2_exec($con, $s5);
           // var_dump($s5, db2_stmt_errormsg());
           $row5 = db2_fetch_assoc($r5);
           $rcnt = $row5['RCNT'];
           
           
           if ($rcnt === 0){
               $s6 = "Insert into fmanstq/toursheet_time (SHEETID, TIMECODE, EVENTDATE, EVENTTIME, EVENTDUR, TIMECAT, TIMEID, EVENTCOMMENT) VALUES(
               $sheetid,
               '$timecode',
               '$edate',
               '$etime',
               $edur,
               '$timeCat',
               $timeid,
               '$comment'
               
               ) with NC";
               $r6 =    db2_exec($con, $s6);
               
                echo "<td>$s</td>";
                echo "<td>" . db2_stmt_errormsg() . "</td>";
               
               if ($etime >= "06:00:00" and $etime < '8:00:00') $shift = 'Days'; else $shift = 'Nites';
               $s7 = "Insert Into Fmanstq.toursheet_team (SHEETID, SHIFT, SHEETDATE, SHIFTSTARTDATE, SHIFTSTARTTIME) values($sheetid, '$shift', current date,
               '$edate',
               '$etime') with NC";
               $r7 = db2_exec($con, $s7);
               $sts = "Insert into Fmanstq.toursheet (SHEETID, REEL, REELID) values($sheetid, $sheetid,'$edate')with NC";
               $rts = db2_exec($con, $sts);
               // var_dump($sts, db2_stmt_errormsg());
               
           } else {
                echo "<td>Record Exists </td><td>&nbsp</td>";
           }
           
            echo "</tr>";
           
       }
        echo "</table>";
   }
   
   public static function is_url_exist($url){
       $ch = curl_init($url);
       curl_setopt($ch, CURLOPT_NOBODY, true);
       curl_exec($ch);
       $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
       
       if($code == 200){
           $status = true;
       }else{
           $status = false;
       }
       curl_close($ch);
       return $status;
   }
   
   
}

?>