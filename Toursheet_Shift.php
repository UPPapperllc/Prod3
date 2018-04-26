<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


ini_set('memory_limit', '-1');
ini_set('time_limit', -1);
ignore_user_abort();

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require_once '../uphead.php';
require_once 'UPClass2.php';
//require 'TSClass.php';
$user = strtoupper(trim($_SERVER['PHP_AUTH_USER']));
date_default_timezone_set('US/Eastern');
if (isset ($_GET['action'])) $action = $_GET['action']; else $action = 'list';
if (isset($_GET['QC'])) $qc ='Y'; else $qc = 'N';
//var_dump('Action: ',$action, ' QC: ', $qc );


$s = "select sheetid, (trim(reelid) ||substr(digits(reel),4,2)) as ename   
from toursheet where milrun > 99999 and milrun < 999999               ";
$r = db2_exec($con, $s);
while ($row = db2_fetch_assoc($r)){
    $sheetid = $row['SHEETID'];
    $ename = $row['ENAME'];
    $s2 = "Select value from Prod_Reel where eventname = '$ename' and variable = 'Mill Run number'";
    $r2 = db2_exec($con, $s2);
    $row2 = db2_fetch_assoc($r2);
    $newmilrun = $row2['VALUE'];
    if (trim($newmilrun) !== ''){
        $s3 = "Update Toursheet set MilRun = $newmilrun where sheetid = $sheetid with NC";
        db2_exec($con, $s3);
        
    }
    
    
}


//****************************************************************
// List Action
//****************************************************************
if (trim($action) == 'list'){

    if (isset($_GET['StartDate']))$indate = substr($_GET['StartDate'],0,10); else $indate = date('Y-m-d');
//$indate = substr($_GET['StartDate'],0,10);
$thrudate = strtotime($indate);
$td = date('Y-m-d',$thrudate);
//$dts = $_GET['DTS'];
if (isset($_GET['Shift'])){
    $inshift = $_GET['Shift'];
    if (trim($inshift) == 'Day'){
        $stime  = '06.00.00';
        $etime = '17.59.59';
        $fdate = $indate;
        $tdate = $indate;
    } else {
        $inshift = 'Nite';
        $stime  = '18.00.00';
        $etime = '05.59.59';
        $thrudate1 = strtotime($indate);
        $tdate = date('Y-m-d', strtotime("+1 day", $thrudate1));
        $fdate = $indate;
    }
    
    
}else{
    $ctime = date('H:i:s');
    if ($ctime >= '06:00:00' and $ctime < '18:00:00'){
        $inshift = 'Day';
        $stime  = '06.00.00';
        $etime = '17.59.59';
        $fdate = date('Y-m-d',strtotime($indate));
        $tdate = date('Y-m-d',strtotime($indate));
    } else {
        $inshift = 'Nite';
        $stime  = '18.00.00';
        $etime = '05.59.59';
        $thrudate1 = strtotime($indate);
        $tdate = date('Y-m-d', strtotime("+1 day", $thrudate1));
        $fdate = date('Y-m-d',strtotime($indate));
    }
}
$fts = $fdate . '-' . $stime . '.000000';
$tts = $tdate . '-' . $etime . '.000000';
//var_dump($indate, $td, $thrudate, $fromDate);
//$fd = date('Y-m-d',$fromDate);
//echo $fd . ', ' . $td;

$data =  array();
$ptime = 0;
$dtime = 0;
$pwgt = 0;
$ttpl = 0;
$ecode =  ' ';
$ecat = ' ';
$comment = ' ';
$set = ' ';
$milr = ' ';
$mili = 0;
$s = "SELECT  * FROM TOURSHEETBYEVENTDATE                
WHERE timestamp(eventdate , eventtime) >= '$fts' and 
timestamp(eventdate , eventtime) <= '$tts' ORDER BY  
timestamp(eventdate , eventtime) ";                                   
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
while ($row = db2_fetch_assoc($r)){
    $sheetid = $row['SHEETID'];
    $pwgt = 0;
    $s2 = "SELECT TIMESTAMPDIFF(4, CHAR(timestamp(eventdate, eventtime)- 
F_GETPTS(timestamp(eventdate, eventtime)))) as PT                   
 FROM fmanstq.toursheetv2 Where SHEETID = $sheetid";
    $r2 = db2_exec($con, $s2);
//    var_dump($s2, db2_stmt_errormsg());
    $row2 = db2_fetch_assoc($r2);
    
    $ss = "SELECT eventdate, eventtime,                     
 F_GETPTS(timestamp(eventdate, eventtime)) as PTS
from toursheet_time where sheetid = $sheetid        ";
    $rss = db2_exec($con, $ss);
  //  var_dump($ss, db2_stmt_errormsg());
   $rowss = db2_fetch_assoc($rss);
    $theshift = $inshift;
    if ($rowss['PTS'] < $fts) {
        if (trim($inshift) == 'Day') $theshift = 'Nite';
        if (trim($inshift) == 'Nite') $theshift = 'Day';
    }
    
    
$edur = $row2['PT'];
        IF ($row['TIMEID']>= 70){
            $dtime = $edur ;
            $ptime = 0;
        } else {
            $dtime = 0; 
            $ptime = $edur ;
        }
    
    $comment = trim($row['EVENTCOMMENT']);
    $ecat = $row['TIMECAT'];
    $ecode = $row['TIMECODE'];
    $pwgt = $row['PROD_WGT'];
    $tid = $row['TIMEID'];
    
    $tppgrade = $row['GRADE'];
    $stpp = "select * from toursheet_tpp where grade = '$tppgrade' ";
    // $rtpp = db2_exec($con, $stpp);
    // $rowtpp = db2_fetch_assoc($rtpp);
    // $tpp = $rowtpp['TPP'];
    // $lineEtime = ($pwgt * $tpp)/60;
    $tpp = 0;
    $lineEtime = 0;
    
    $x['SHEETID'] = $sheetid;
    $x['SHIFT'] = $theshift;
    $x['GRADE'] = $row['GRADE'];
    $x['BW'] = $row['BSWGT'];
    $x['MILRUN'] = $row['MILRUN'];
    $x['ITEM'] = number_format($row['DIAM']);
    $x['REELCODE']= $row['REELID'];
    $x['REEL'] = $row['REEL'];
    $x['EVENTDATE'] = $row['EVENTDATE'];
    $x['EVENTTIME'] = str_replace('.', ':',$row['EVENTTIME']);
    $x['PTIME'] = $ptime;
    $x['DTIME'] = $dtime;
    $x['PWGT'] = $pwgt;
    $x['TID'] = number_format($tid);
    $x['ECODE'] = $ecode;
    $x['ECAT'] = $ecat;
    $x['COMMENT'] = $comment;
    $x['SET'] = $set;
    $x['EID'] = $row['EVENTID'];
    $x['TPP'] = $tpp;
    $x['TPL'] = $lineEtime;
    $ttpl += $lineEtime;
        
  $data[] = $x;
    

}
//Return result to jTable
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;

print json_encode($jTableResult);

}

//****************************************************************
// Update Action
//****************************************************************
if (trim($action) == 'update'){
    $sheetid = $_POST['SHEETID'];
   $grade = $_POST['GRADE']; // header
   $bw =  $_POST['BW']; // header
   $milrun =  $_POST['MILRUN']; // header
   $diam =    $_POST['ITEM']; // header
   $reelcode =  $_POST['REELCODE']; 
   $reel =  $_POST['REEL'];
   $edate =          $_POST['EVENTDATE'];
   $etime =         str_replace(':', '.', $_POST['EVENTTIME']);
   $ptime = $_POST['PTIME'];
   $dtime =              $_POST['DTIME'];
   $pwgt =  $_POST['PWGT'];
   $set =                  $_POST['SET'];
   $comment =  $_POST['COMMENT'];
   $tid = $_POST['TID'];
    
$eventTime = $ptime + $dtime;
  if (trim($set) == '') $set = 0;
  $sc = "Select * from       
Toursheet_Event_Cat  where TID = $tid";
  $rc= db2_exec($con, $sc);
  $rowcode = db2_fetch_assoc($rc);
 $cat = $rowcode['TCAT'];
 $tcode = $rowcode['TCODE'];
  
   $s = "Update Toursheet_time set
REEL = $reel, 
REELID = '$reelcode',
REELSET = $set,
TIMECAT = '$cat',
TIMECODE = '$tcode',
TIMEID = $tid,
EVENTDATE = '$edate',
EVENTTIME = '$etime',
EVENTDUR = $eventTime,
EVENTCOMMENT = '$comment',
PROD_WGT = $pwgt
where sheetid = $sheetid WITH NC";
  $r = db2_exec($con, $s);
  $msg = db2_stmt_errormsg();
  //     var_dump($s, db2_stmt_errormsg());
   
  // if set is greater than zero set timeid to 90;
  if ((float)$set > 0) {
      $sb = "Update toursheet_time set TIMEID = 90 where sheetid = $sheetid with NC;";
      $rb = db2_exec($con, $sb);
      $sb = "Update toursheet set GradeChange = 'Y' where sheetid = $sheetid with NC;";
      $rb = db2_exec($con, $sb);
      //    var_dump($sb, db2_stmt_errormsg());
  }
  
  
   $s2 = "Update Toursheet set
MILRUN = $milrun,
DIAM = $diam, 
GRADE = '$grade',
BSWGT = '$bw'

where sheetid = $sheetid with NC";   
$r2=db2_exec($con, $s2);
  

 // 
$msg2 = db2_stmt_errormsg();
 
   $jTableResult = array();
   if(trim($msg) == '' and trim($msg2) == '') {
   $jTableResult['Result'] = "OK";
 
   } else {
       $jTableResult['Result'] = "Error";
 $jTableResult['Message'] = $msg . '/' . $msg2;
 $jTableResult['Records'] = $msg;
   }
  // $jTableResult['Record'] = $s . ' / ' . db2_stmt_errormsg();
   print json_encode($jTableResult);
   
    
}


//****************************************************************
// Create Action
//****************************************************************
if (trim($action) == 'create'){
    $inshift = $_GET['Shift'];
   
    
    $smax = "Select max(sheetid) as max from toursheet";
    $rmax = db2_exec($con,$smax);
    $rowmax = db2_fetch_assoc($rmax);
    $nextid = $rowmax['MAX'];
    $nextid += 1;
  //  var_dump('Action Create Started - Next ID; ', $nextid);
    
    $sheetid = $nextid;
    if ($qc == 'Y') {
        $grade = ' ';
        $bw =  0;
        $milrun = 0;
        $diam =   0;
        $reelcode =  date('Y-m-d-h:i:s');
        $reel =  0;
        $edate = date('Y-m-d');
        $etime = date('h:i:s');
        $ptime = 0;
        $dtime = 0;
        $pwgt =  0;
        $set =   0;
        $comment =  $_GET['QC'];
        $cat = 'Comment';
        $tid = 40;
    } else {
    $grade = $_POST['GRADE']; // header
    $bw =  $_POST['BW']; // header
    $milrun =  $_POST['MILRUN']; // header
    $diam =    $_POST['ITEM']; // header
    $reelcode =  $_POST['REELCODE'];
    $reel =  $_POST['REEL'];
    $edate =          $_POST['EVENTDATE'];
    $etime =         str_replace(':', '.', $_POST['EVENTTIME']);
    $ptime = $_POST['PTIME'];
    $dtime =              $_POST['DTIME'];
    $pwgt =  $_POST['PWGT'];
    $set =                  $_POST['SET'];
    $comment =  $_POST['COMMENT'];
   // $cat = $_POST['ECAT'];
    $tid = $_POST['TID'];
    }
    
   $sc = "Select * from Toursheet_Event_Cat  where TID = $tid";
    $rc= db2_exec($con, $sc);
    $rowcode = db2_fetch_assoc($rc);
    $cat = $rowcode['TCAT'];
        $tcode = $rowcode['TCODE'];
    
    
    $eventTime = (float)$ptime + (float)$dtime; 
    if ((float)$reel == 0) $reel = $nextid;
    $x['SHEETID'] = $nextid;
    $x['GRADE'] = $grade;
    $x['BSWGT'] = $bw;
    $x['MILRUN'] = $milrun;
    $x['ITEM'] = $diam;
    $x['REELCODE'] = $reelcode;
    $x['REEL'] = $reel;
    $x['EVENTDATE'] = $edate;
    $x['EVENTTIME'] = $etime;
    $x['PTIME'] = $ptime;
    $x['DTIME'] = $dtime;
    $x['PWGT'] = $pwgt ;
    $x['SET'] = $set ;
    $x['COMMENT'] = $comment;
    $x['TID'] = $tid;
    $data=array();
    $data[] = $x;
    
    $s = "Insert into toursheet (SHEETID, PRODORD, GRADE, BSWGT, DIAM, REEL, REELID, MILRUN, GRADECHANGE) values(
$sheetid, 
$milrun,
'$grade',
$bw,
$diam,
$reel,
'$reelcode',
$milrun,
'N'
) with NC";    
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
$msg = db2_stmt_errormsg();
    
$s2 = "Insert into toursheet_time (SHEETID, REEL, REELID, REELSET, TIMECODE, EVENTDATE, EVENTTIME, EVENTDUR, EVENTCOMMENT, 
TIMECAT, TIMEID, EVENTID, REELREF, PROD_WGT) values(
$sheetid,
$reel, 
'$reelcode',
$set,
'$tcode',
'$edate',
'$etime',
$eventTime,
'$comment',
'$cat',
$tid,
0,
' ',
$pwgt
) with NC"; 
$r2 = db2_exec($con, $s2);
$msg2 = db2_stmt_errormsg();

$s3 = "INSERT INTO FMANSTQ/TOURSHEET_TEAM (SHEETID, SHEETDATE, REELID, REEL) VALUES($sheetid,'$edate', '$reelcode', $reel) with NC" ;
$r3 = db2_exec($con, $s3);
setshift ($con, $sheetid, $inshift);
    
   // var_dump('Msg1: ', $msg, ' Msg2: ', $msg2);
    $jTableResult = array();
    if(trim($msg) == '' and trim($msg2) == '') {
        $jTableResult['Result'] = "OK";
        
    } else {
        $jTableResult['Result'] = "Error";
        $jTableResult['Message'] = $msg . '/' . $msg2;
        $jTableResult['Records'] = $msg;
    }
    $jTableResult['Record'] = $data;
    print json_encode($jTableResult);
    
    
}
    


if ($qc == 'w'){
?>
<script>
window.close();
window.opener.reload()
</script>
<?php 
}

function setshift($con, $sheetid, $shiftid){
    // has the shift been defined?
    $s = "Select * from FMANSTQ/TOURSHEET_TEAM WHERE SHEETID = $sheetid";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    if (trim($row['BACKTENDER'])== ''){
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








