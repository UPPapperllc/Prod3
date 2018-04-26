<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require_once '../uphead.php';
$user = strtoupper(trim($_SERVER['PHP_AUTH_USER']));
if (isset ($_GET['action'])) $action = $_GET['action']; else $action = 'list';

//var_dump($customer, $project, $con);
$colors = array('#FF0000', '#BF3030', '#A50000', '#FF4040', '#FF7373', '#FF7400', '#BF7130', '#A54BC0', '#FF9640', '#FFB273', '#CD0074', '#992667', '#85004B', '#E6399B', '#E6670F', '#0DCC00', '#269925', '#008500', '#E63639', '#67E667');



  
    
  
$s = "Select  substr(grd8,1,2) as g , substr(grd8, 10,3) as S
from Prod_Schedule where SchedSTAT not in('complete', 
'Closed','closed','Complete')                         
group by substr(grd8,1,2)  , substr(grd8, 10,3)       ";
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());

$saveg = '';
$ci = 0;
while($grow = db2_fetch_assoc($r)){
    $rid = $grow['G']. '-' . $grow['S'];
    $g= $grow['G'];
    
//    if (trim($g) !== trim($saveg)){
  //      if (trim($saveg) !== '') $recources[] = $xx;
    $rid = $grow['G']. '-' . $grow['S'];
    $xx['id'] = $rid;
    $xx['grade'] = $rid;
    $xx['title'] = $rid;
    $xx['eventColor'] = $colors[$ci];
  //  $saveg = $g;
  //  $z=array();
  //  $xx['children']= array();
  //  }
  //  $z['id'] = $rid;
  //  $z['title']= $grow['S'];
  //  $xx['children'][] = $z;
    $recources[] = $xx;
    $ci +=1;
    if($ci == 20) $ci = 0;
}
$xx['id'] = 99;
$xx['grade'] = 99;
$xx['title'] = 'Finished';
$xx['eventColor'] = 'midnightblue';
$recources[] = $xx;

    $s = "Select * from Prod_Schedule where SchedSTAT not in('complete', 'Closed','closed','Complete') ORDER by SCHTS";
    $r = db2_exec($con, $s);

    //Add all records to an array
    $rows = array();
    while($row = db2_fetch_assoc($r )  )
    {
        //  var_dump($start);
        $grd = $row['GRD8'];
       // 300364802016
        $g = substr($grd,0,2);
        $s = substr($grd, 9, 3 );
        $rid = $g. '-' . $s;
        $start = substr($row['SCHTS'], 0 ,10) . 'T' . substr($row['SCHTS'], 11 ,2) .':' . substr($row['SCHTS'], 14 ,2) . ':' . substr($row['SCHTS'],17,2);
        $end= substr($row['SCHETS'], 0 ,10) . 'T' . substr($row['SCHETS'], 11 ,2) .':' . substr($row['SCHETS'], 14 ,2) . ':' . substr($row['SCHETS'],17,2);
        $x['id'] = $row['PROD_ID'];
        if (trim($row['SCHEDSTAT']) == 'Finished'){
            $x['resourceId'] =  99;
        } else {
        $x['resourceId'] =  $rid;
        }
        $x['start'] = $start;
        $x['end'] = $end;
        $x['title'] = $row['MILRUN'] . ' ' . trim($row['ADLINFO']) . ' ' . trim($row['ADLNOTES']);
        
        $rows[] = $x;
        
        
    }
    
  
    
    
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['events'] = $rows;
    $jTableResult['resources'] = $recources;

    print json_encode($jTableResult);
    

