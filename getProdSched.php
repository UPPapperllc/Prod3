<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require_once '../uphead.php';
require_once 'UPClass2.php';
$user = strtoupper(trim($_SERVER['PHP_AUTH_USER']));
if (isset ($_GET['action'])) $action = $_GET['action']; else $action = 'list';

//var_dump($customer, $project, $con);

// setTransitTime($con);


if (trim($action) == 'list'){
    if (isset($_GET['jtStartIndex']))  $start = $_GET['jtStartIndex']; else $start = 1;
    if (isset($_GET['jtPageSize']))  $pagesize = $_GET['jtPageSize'];else $pagesize = 25;
  
    $orderby2='';
    if (isset($_GET['jtSorting'])){
        if (substr(trim($_GET['jtSorting']),0, 7) == 'RBYDATE'){
      
            $_GET['jtSorting'] =  str_replace('RBYDATE', " cast(('20' || Ryear || '-' || Rmonth || '-' || rday) 
as date )",$_GET['jtSorting'] ) ;
            $orderby2 = $orderby = "Order by " . $_GET['jtSorting'];
        } else  $orderby = "Order by " . $_GET['jtSorting'];
    } else $orderby = "Order by SCHEDSTAT DESC, SHIFT, RUNSTAT , SUBSTR(GRD8, 1,2) || SUBSTR(GRD8, 11,2), SCHTS ";
    
    
    $s = "Select * from Prod_Schedule where SchedSTAT not in('complete', 'Closed','closed','Complete') $orderby";
    $r = db2_exec($con, $s,array('cursor' => DB2_SCROLLABLE));
    //var_dump($s, db2_stmt_errormsg());
    $i = $start    + $pagesize;
    if ($start == 0) $start = 1;
    //Add all records to an array
    $rows = array();
    while($row = db2_fetch_assoc($r, $start) and $start< $i )
    {
        
        $twgt = 0;
        $trolls = 0;
        $textwgt = 0;
        $pwgt = 0;
        $pcnt = 0;
        
        $milrun = $row['MILRUN'];
        $runitem = $row['RUNITEM'];
        $s2 =  $smp = "select distinct MILORD, SHIP#  from milposp f1
        
        where milrn# = $milrun   and item# = $runitem            ";
        $smpr = db2_exec($con, $smp);
       while($smprow = db2_fetch_assoc($smpr)){
            $ord = $smprow['MILORD'];
            $shp = $smprow['SHIP#'];
            if (trim($ord) !== ''){
            $sord = "Select WGTGRS, ROLLS# from ORDATEP where MILORD = '$ord' and SHIP# = $shp";
            $rord = db2_exec($con, $sord);
          //  var_dump($sord, db2_stmt_errormsg());
            $roword = db2_fetch_assoc($rord);
            $slp = "Select sum(ROLWGTA) AS wgt, COUNT(*) AS cnt from LPMAST where MILORD= '$ord' and shipnum = $shp and lpstat <> 'CULLED'";
            $rlp = db2_exec($con, $slp);
         //   var_dump($slp, db2_stmt_errormsg());
            $lp1row = db2_fetch_assoc($rlp);
           
            $twgt += $roword['WGTGRS'];
            $trolls += $roword['ROLLS#'];
            
            $pwgt += $lp1row['WGT'];
            $pcnt += $lp1row['CNT'];
            
            }
            
       }
 //      $srd = "select min(                                                   
 //cast(('20' || Ryear || '-' || Rmonth || '-' || rday)         
 //as date )) as ReqDate from milposp f1                        
 //left join ordentp f2 on f2.milord = f1.milord                
 //left join ordatep f3 on f3.milord = f1.milord and f3.ship# = 
//f1.ship#                                                      
// where milrn# = $milrun ";
//       $rrd = db2_exec($con, $srd);
//       $rowrd = db2_fetch_assoc($rrd);
   //    var_dump($srd, db2_stmt_errormsg(), $rowrd);
   $s5 = "Select max(truckdays) as TransitDays from milposp f1
 left join ordentp f2 on f2.milord = f1.milord      
left join transittime f4 on f2.custsh = f4.cust     
 where milrn# = $milrun   ";
   $r5=db2_exec($con, $s5);
   $row5 = db2_fetch_assoc($r5);
 
       $pdate = strtotime($row['RUNSTAT'] ."- ". $row5['TRANSITDAYS']." days");
       
       $trucks = ceil($twgt/44000);
       if ((float)$pwgt > 0){
           $pcntprd = ((float)$pwgt/(float)$twgt) * 100;
           $pprd = '(' . number_format($pcntprd,2) . '%)';
       }  else $pprd = '';
       $row['PRODUCED'] = number_format($pwgt) . $pprd .'<br>' . number_format($pcnt) ;
        $row['PCNT'] =  number_format($pcnt);
        $row['REQ'] =  number_format($twgt) . ' (' . $trucks . ')' .'<br>' . number_format($trolls);
        $row['RCNT'] = number_format($trolls);
        $row['title'] = 'Milrun: ' . $milrun;
        $row['TRAND'] =  number_format($row5['TRANSITDAYS']) .'<br>' .date('m/d',$pdate);
        $row['PDATE'] =  date('m/d',$pdate);
        
        


        $rows[] = $row;
        $start++;
    
    }
    
    
    $s2 = "Select count(*) as CNT from Prod_Schedule where SchedSTAT not in('complete','Closed', 'closed','Complete')";
    $r2 = db2_exec($con, $s2);
    $row2 = db2_fetch_assoc($r2);
    
    $s = "Select max(SCHDATE) as Max from Prod_Schedule";
    $r=db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    
    $sdate =  $row['MAX'];
    $s = "Select max(SCHTIME) as MaxT from Prod_Schedule where SCHDATE = '$sdate'";
    $r=db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $stime = $row['MAXT'];
    
    
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['SDate'] = $sdate;
    $jTableResult['STime'] = $stime;
    $jTableResult['TotalRecordCount'] = $row2['CNT'];
    print json_encode($jTableResult);
    
} elseif (trim($action) == 'create'){
    $s1 = "Select max(prod_id) as LASTREC from Prod_Schedule";
    $r1 = db2_exec($con, $s1);
    $row1 = db2_fetch_assoc($r1);
    $lastrec = $row1['LASTREC'];
    $recid = $lastrec + 1;
    
    $prod_id = $recid;
    $rev = 1;
  
    $milrun  = $_POST['MILRUN'];
    $grd8 = $_POST['GRD8'];
    $adlinfo = $_POST['ADLINFO'];
    $adlnotes = $_POST['ADLNOTES'];
 
    $esthours = $_POST['ESTHOURS'];
    $shift = trim($_POST['SHIFT']);
    
    $schdate = $_POST['SCHDATE'];
    $schedstat = $_POST['SCHEDSTAT'];
    
    $schtime = $_POST['SCHTIME'];

    
 
    
    $s = "Insert into Prod_Schedule values(
    $rev,
    '$milrun',
    '$grd8',
    '$adlinfo',
    '$adlnotes',
    0,
    0,
    0,
    $esthours,
    ' ',
     current timestamp, 
    '$schdate',
    '$schedstat',
    ' $shift',
    '$schtime',
    $prod_id,
     current timestamp,
      current timestamp,
 current timestamp,
    0 
    ) with NC";
    $r = db2_exec($con, $s);
    
    $su = "Update Prod_Schedule set
    SCHTS = TIMESTAMP(SCHDATE, SCHTIME),
    SCHETS = TIMESTAMP(SCHDATE, SCHTIME) + (ESTHOURS * 60) MINUTES
    where PROD_ID = $prod_id with NC";
    $ru = db2_exec($con, $su);
    
    
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $s . ' / ' . db2_stmt_errormsg();
    print json_encode($jTableResult);
    
    
    //$lcb = $_POST['LCB'];
    
} elseif (trim($action) == 'update'){
    $prod_id = $_POST['PROD_ID'];
    //$rev = $_POST['REV'] + 1;
    
    $milrun  = $_POST['MILRUN'];
    $grd8 = $_POST['GRD8'];
    $adlinfo = $_POST['ADLINFO'];
    $adlnotes = $_POST['ADLNOTES'];

    $esthours = $_POST['ESTHOURS'];
    $shift = trim($_POST['SHIFT']);
   
    $schdate = $_POST['SCHDATE'];
    $schedstat = $_POST['SCHEDSTAT'];
    
    $schtime = $_POST['SCHTIME'];
    $lcdt = ''; // current date time
    // $schts = $schdate + '-' + ; // start date and start time
    // $schets = ''; // start date/time + estimated hours
    $refrun = 0;
    
    $s = "Update Prod_Schedule set
REV = REV+1,
MILRUN = '$milrun',
GRD8 = '$grd8',
ADLINFO = '$adlinfo',
ADLNOTES = '$adlnotes',
ESTHOURS = $esthours,
SHIFT = '$shift',
SCHDATE = '$schdate',
SCHEDSTAT = '$schedstat',

SCHTIME = '$schtime',
LCDT = current timestamp

where prod_id = $prod_id with NC";
    $r = db2_exec($con, $s);
 
    $su = "Update Prod_Schedule set
    SCHTS = TIMESTAMP(SCHDATE, SCHTIME),
    SCHETS = TIMESTAMP(SCHDATE, SCHTIME) + (ESTHOURS * 60) MINUTES
    where PROD_ID = $prod_id with NC";
    $ru = db2_exec($con, $su);
    
    
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $s . ' / ' . db2_stmt_errormsg();
    print json_encode($jTableResult);
    
    $rtn = UPClass2::setSchedule($con);
    
} elseif (trim($action) == 'delete'){
    $prod_id = $_POST['PROD_ID'];
    $s = "Update Prod_Schedule  set SCHEDSTAT = 'Complete', LCDT  = current timestamp where
    PROD_ID = $prod_id";
    $r = db2_exec($con, $s);
    
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $s . ' / ' . db2_stmt_errormsg();
    print json_encode($jTableResult);
    
}function setTransitTime($con){
    $s = "Select distinct(f2.custsh) as CMCUST from Prod_Schedule f5
left join milposp f1 on f1.milrn# = f5.milrun
left join ordentp f2 on f2.milord = f1.milord
        
 where SchedSTAT not in('complete',
'Closed','closed','Complete')
     and   not exists
(Select * from transittime where cust = custsh)
                                                ";
    $r = db2_exec($con, $s);
    while ($row = db2_fetch_assoc($r)){
        $cust = $row['CMCUST'];
        $s2 = "Select * from hdcust where CMCUST = $cust";
        $r2 = db2_exec($con, $s2);
        $row2  = db2_fetch_assoc($r2);
      //  var_dump($s2, db2_stmt_errormsg());
        sleep(3);
      //  echo "<br> Loading $cust " . $row2['CMZIP'] . ' ' . $row2['CMCCTY'] . ' ' . $row2['CMST'];
        ?>
     <script>
var url = "DistanceAPI.php?CUST=<?php echo $cust;?>";
window.open(src=url, '<?php echo 'SD' . $cust;?>', "width=400,height=100");
     </script>
     
     <?php 
     
 }
    
    
}