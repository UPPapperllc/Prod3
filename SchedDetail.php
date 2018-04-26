<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require_once '../uphead.php';
require "UPClass2.php";
date_default_timezone_set('US/Eastern');
$user = strtoupper(trim($_SERVER['PHP_AUTH_USER']));
$menu = UPClass2::getMenuOptions();

 $id = $_GET['ID'];
 
 $s = "Select * from prod_schedule where prod_id = $id";
 $r = db2_exec($con, $s);
 $schedrow = db2_fetch_assoc($r);
 
 
 $mr = $schedrow['MILRUN'];
 
 $sp = "Select * from Prod_Schedule where milrun = $mr";
 $rp = db2_exec($con, $sp);
 $rowp = db2_fetch_assoc($rp);
 
 $esthours = $rowp['ESTHOURS'];
 $schedstat = $rowp['SCHEDSTAT'];
 
 $sx = "SELECT min(entryon) as StartDT,                                     
TIMESTAMPDIFF(4, CHAR(CURRENT TIMESTAMP - min(entryon))) as LiveM 
  FROM prod_reel                                         
  where variable = 'Mill Run number' and value = '$mr' and date(entryon)  >= current date   ";
 $rx = db2_exec($con, $sx);
 $rowx = db2_fetch_assoc($rx);
 
 
 $sp = "select distinct cmcna1  as CUST, custsh , truckdays from milposp f1
 left join ordentp f2 on f2.milord = f1.milord
 left join prhdsdata.hdcust f3 on custsh  = cmcust
left join transittime f4 on cmcust = f4.cust
 where milrn# = $mr               ";
 $spr = db2_exec($con, $sp);
 $spc = '';
 $custlist = '<table border = "1">';
 while($sprow = db2_fetch_assoc($spr)){
     $custsh = $sprow['CUSTSH'];
     $custlist .= "<tr><td>".
   "<a href = 'distanceAPI.php?CUST=$custsh' target='dapi' >$custsh</a></td><td>". 
     $sprow['CUST'] . "</td><td>". 
     $sprow['TRUCKDAYS']."</td></tr>" ;
 }
$custlist .=  "</table>";
 
 
 $spbt = "select distinct cmcna1  as CUST, cust# from milposp f1
 left join ordentp f2 on f2.milord = f1.milord
 left join prhdsdata.hdcust f3 on cust#  = cmcust
 where milrn# = $mr               ";
 $spbtr = db2_exec($con, $spbt);
 
 $biltolist = ' <table border="1">';
 while($spbtrow = db2_fetch_assoc($spbtr)){
     $biltolist .= "<tr><td>".$spbtrow['CUST#'] . "</td><td>". $spbtrow['CUST'] . "</td></tr>" ;
  
 }
 $biltolist .=  "</table>";
 
 $ordcnt = 0;
 $twgt = 0;
 $trolls = 0;
 $textwgt = 0;
 $pwgt = 0;
 $pcnt = 0;
 $ttrem = 0;
 $spos = "Select distinct milord, ship#, ROLWTH, ROLFRC from milposp where milrn# = $mr";
 $rpos = db2_exec($con, $spos);
 $ordlist = '<table border = "1" class="table table-sm "><tr><th>Order</th><th>Shipment</th><th>Grade</th><th>Ordered<br>Weight</th><th>Ordered<br> Rolls</th><th>Roll Width</th><th>Avr<br> Wgt/Roll</th><th>Wgt by<br>Average</th><th>Prod<br> Wgt</th><th>Prod<br> Rolls</th><th>Remaining<br>Rolls</th><th>Est Time<br>to Prod</th><th>Culled <br>Wgt</th><th>Culled<br> Rolls</th><th>Prod run<br>Qtys</th></tr>';
 
 While ($rowpos = db2_fetch_assoc($rpos)){
     $milord = $rowpos['MILORD'];
     $ship = $rowpos['SHIP#'];
     $width = $rowpos['ROLWTH'] . ' ' . $rowpos['ROLFRC'];
 //    echo "<br>";
 //    var_dump($milord, $ship);
 $smp = "select * from ordentp f2 
 left join ordatep f3 on f3.milord = 
 '$milord' and f3.ship# = $ship
 where f2.milord = '$milord'                ";
 $smpr = db2_exec($con, $smp);
// var_dump($smp, db2_stmt_errormsg());
 $smprow = db2_fetch_assoc($smpr);
     $ord = $smprow['MILORD'];
     $shp = $smprow['SHIP#'];
     $slp = "Select sum(ROLWGTA) AS wgt, COUNT(*) AS cnt from LPMAST where MILORD= '$ord' and shipnum = $shp and lpstat <> 'CULLED'";
         $rlp = db2_exec($con, $slp);
     $lp1row = db2_fetch_assoc($rlp);
     $sclp = "Select sum(ROLWGTA) AS wgt, COUNT(*) AS cnt from LPMAST where MILORD= '$ord' and shipnum = $shp and lpstat = 'CULLED'";
     $rclp = db2_exec($con, $sclp);
     $lp1crow = db2_fetch_assoc($rclp);
     $grd = $smprow['GRD8'];
     
     
     $tpp = UPClass2::getTPP($con, $grd);
     $avrwgt = UPClass2::getAWPR($con, $grd);
 
     $extwgt = $avrwgt * $smprow['ROLLS#'];
     
    
     
     $rowclass = $rowclass = ' ';
     if (number_format($smprow['ROLLS#']) == number_format( $lp1row['CNT'] )) $rowclass = 'class="success"';
     if (number_format($smprow['ROLLS#']) < number_format( $lp1row['CNT'] )) $rowclass = 'class="danger"';
     if (number_format($smprow['ROLLS#']) > number_format( $lp1row['CNT'] )) $rowclass = 'class="warning"';
     if (number_format( $lp1row['CNT'] ) == 0) $rowclass = ' ';
     $ordlist .= "<tr $rowclass>";
     $ord = $smprow['MILORD'];
     $shp = $smprow['SHIP#'];
     $shipvia = $smprow['CARVIA'];
     $rdate = $smprow['RMONTH'] . '/' . $smprow['RDAY'] . '/20' . $smprow['RYEAR']; 
     if (trim($shipvia) == 'R') $sva= 'Rail'; else $sva = 'Truck';
     $detailurl = 'OrderDetail.php?O='. $smprow['MILORD'].'&S='.$smprow['SHIP#'];
$ordlist .= "<td><a href='$detailurl' target='OrdDetail'>". $smprow['MILORD']."</a></td>";
$ordlist .= "<td>". $smprow['SHIP#'].'/'.$sva .'<br>'. $rdate ."</td>";
$ordlist .= "<td >".$smprow['GRD8'] ."</td>";
$ordlist .= "<td style='text-align: right;' >". number_format($smprow['WGTGRS']) ."</td>";
$ordlist .= "<td style='text-align: right;' >". number_format($smprow['ROLLS#']) . "</td>"  ;
$ordlist .= "<td style='text-align: right;' >". $width . "</td>"  ;
$ordlist .= "<td style='text-align: right;' >". number_format($avrwgt) . "</td>"  ;
$ordlist .= "<td style='text-align: right;' >". number_format($extwgt) . "</td>"  ;
$ordlist .= "<td style='text-align: right;' >" . number_format( $lp1row['WGT']) . "</td>";
$ordlist .= "<td style='text-align: right;' >" . number_format( $lp1row['CNT'] ) . "</td>";
$remrolls = (float)$smprow['ROLLS#'] - (float)$lp1row['CNT'];
if ($remrolls < 0) $remrolls = 0;
$etimerem = UPClass2::getESTTIME($con, $grd, $remrolls);
$ttrem += $etimerem;
$ordlist .= "<td style='text-align: right;' >" . number_format( $remrolls ) . "</td>";
//$ordlist .= "<td style='text-align: right;' >" . number_format( $tpp,6 ) . "</td>";
$ordlist .= "<td style='text-align: right;' >" . number_format( $etimerem,6 ) . "</td>";
//$ordlist .= "<td style='text-align: right;' >" . number_format( $lp1row['CNT'] ) . "</td>";
$ordlist .= "<td style='text-align: right;' >" . number_format( $lp1crow['WGT'] ). "</td>";
$ordlist .= "<td style='text-align: right;' >" . number_format(  $lp1crow['CNT'])."</td>";

$sor = "select  milrun, count(*) as orcnt from lpmast where milord = '$ord' and
shipnum = $shp                                                     
 group by milrun                                                ";
$ror = db2_exec($con, $sor);
$ordlist .= "<td><table border='1'>";
while ($rowor = db2_fetch_assoc($ror)){
    $ordlist .= '<tr><th style="color: darkblue;">' . $rowor['MILRUN'] . "</th><td style='color: darkblue;'>" . $rowor['ORCNT'] . '</td></tr>';
}
$ordlist.= "</table></td>";

$ordlist .= "</tr>" ;
     $ordcnt+=1;
     $twgt += $smprow['WGTGRS'];
     $trolls += $smprow['ROLLS#'];
     $textwgt += $extwgt;
     $pwgt += $lp1row['WGT'];
     $pcnt += $lp1row['CNT'];
     
 }
 
 
 $xwgt = round(100 * ($pwgt/$twgt),2);
 $xcnt = 100 * ($pcnt/$trolls);
 $ordlist .=  "<tr><td>Total</td><td> Count</td><td>" . number_format($ordcnt) . "</td><td style='text-align: right;' >".number_format($twgt)."</td><td style='text-align: right;' >".number_format($trolls). "</td><td>&nbsp</td><td style='text-align: right;' >".number_format($textwgt)."</td><td style='text-align: right;' >".number_format($pwgt). "<br>".number_format($xwgt,2) .  "%</td><td style='text-align: right;' >".number_format($pcnt). "<br>".number_format($xcnt,2) ."%</td></tr>";
 $ordlist .=  "</table>";
 
 $etime = substr($schedrow['SCHETS'], 0,10) . ' ' . substr($schedrow['SCHETS'], 11,2) . ':' . substr($schedrow['SCHETS'], 14,2) . ':' . substr($schedrow['SCHETS'], 17,2);
 $new_etime = strtotime(SUBSTR($schedrow['SCHETS'], 0, 10) . ' ' . SUBSTR($schedrow['SCHETS'], 11, 8));
 
 
 if (trim($schedstat) == 'WIP'){
 $now =  time();
 $ttmin  = $ttrem * 60;
 $xtime = '+ ' . (int)$ttmin . ' minutes ';
 $edtime = strtotime($xtime, $now);
 $ectime = date("g:ia - l jS F Y", $edtime);
//echo "<br>$ttrem, $ttmin, $edtime, $xtime";
 $theTime = date("m-d-Y H:i:s",  $now);
//$ectime = (new \DateTime())->add(new \DateInterval($inv));
 $endtime = $ectime . " (". number_format($ttrem,2) . " Hours)";
 $ztime = "Current Time: $theTime";
 } else {
     $ectime =  date("g:ia - l jS F Y",  $new_etime);
     $endtime = $ectime ;
     $new_stime = strtotime(SUBSTR($schedrow['SCHTS'], 0, 10) . ' ' . SUBSTR($schedrow['SCHTS'], 11, 8));
     $starttime = date('m-d-Y H:i:s', $new_stime);
     $ztime = "Start Time: " .  $starttime;
 }
//var_dump($endtime, $new_etime);;
 //$ectime = $endtime;
// var_dump($schedrow['SCHETS'], $etime, $ectime);
 $runstat = "<table border = '1'>";
 $runstat .= "<tr><th> 1st Production Time Stamp today </th><td> " . $rowx['STARTDT'] . "</td></tr>";
 $runstat .= "<tr><th>  Estimated Hours<br>Scheduled </th><td> " . $schedrow['ESTHOURS'] . "</td></tr>";
 if (trim($schedstat) == 'WIP'){
 $runstat .= "<tr><th> Prod Time in Min </th><td > " . $rowx['LIVEM'] . "</td></tr>";
 if ($pcnt > 3){
 $atr = round($rowx['LIVEM'] / $pcnt, 2);
 $runstat .=  "<tr><th> Avr time/roll  </th><td > " . $atr . "</td></tr>";
 $xhours  = ($atr * $trolls)/60;
 $runstat .=  "<tr><th style='text-align: right;'> Z -Estimated hours <br> avr prod/roll</th><td style='text-align: right;'>" .  $xhours . "</td></tr>";
 if ($xhours > $esthours){
     $esthours = $xhours;
     
 }
 

 
 
 $remp  = ((100-$xwgt)/100);
 $rmin =  round(($esthours * $remp) * 60);
 $runstat .= "<tr><th>Time Remaining</th><td style='text-align: right;'>" .  $rmin . "</td></tr>";
 $runstat .= "<tr><th>A -Estmated Hours Remaining</th><td style='text-align: right;'>" .  $ttrem . "</td></tr>";
 
 $new_stime = strtotime( SUBSTR($schedrow['SCHTS'], 0, 10) . ' ' . SUBSTR($schedrow['SCHTS'], 11, 8));
 $starttime = date('Y-m-d H:i:s', $new_stime);
 
 
 //$inv = 'PT' . (int)$rmin . 'M';
 $now = time();
 $ectime = date("m-d-Y H:i:s", strtotime('+'.(int)$rmin.' minutes', $now));
 //$ectime = (new \DateTime())->add(new \DateInterval($inv));
 $runstat .= "<tr><th>B -Estimated End date/time</th><td>" . $ectime  . "</td></tr>";
 
 } else {
     $xhouors = 0;
     $atr = 0;
     
 }
 } else {
     
     $runstat .= "<tr><th> Prod Time in Min </th><td style='text-align: right;'> " . $rowx['LIVEM'] . "</td></tr>";
     if ($pcnt > 3){
         $atr = round($rowx['LIVEM'] / $pcnt, 2);
         $runstat .=  "<tr><th> Avr time/roll  </th><td style='text-align: right;'> " . $atr . "</td></tr>";
         $xhours  = ($atr * $trolls)/60;
         $runstat .=  "<tr><th style='text-align: right;'> C - Estimated hours <br> avr prod/roll</th><td style='text-align: right;'>" .  $xhours . "</td></tr>";
         if ($xhours > $esthours){
             $esthours = $xhours;
             
         }
         
         
         
         
         $remp  = ((100-$xwgt)/100);
         $rmin = ($esthours * $remp) * 60;
         $runstat .= "<tr><th>Time Remaining</th><td style='text-align: right;'>" .  $rmin . "</td></tr>";
         $runstat .= "<tr><th>Estmated Hours</th><td style='text-align: right;'>" .  $esthours . "</td></tr>";
         $stimechar = SUBSTR($schedrow['SCHTS'], 0, 10) . ' ' . SUBSTR($schedrow['SCHTS'], 11, 2) . ':' . SUBSTR($schedrow['SCHTS'], 14, 2) . ':' . SUBSTR($schedrow['SCHTS'], 17, 2);
      //   var_dump($stimechar );
         $new_stime = strtotime( $stimechar );
         $starttime = date('Y-m-d H:i:s', $new_stime);
         $runstat .= "<tr><th>Start Time</th><td style='text-align: right;'>" .  $starttime . "</td></tr>";
         
         
         //$inv = 'PT' . (int)$rmin . 'M';
        // $now = time();
         $ectime = date("m-d-Y H:i:s", strtotime('+'.(int)$rmin.' minutes', $new_stime));
         //$ectime = (new \DateTime())->add(new \DateInterval($inv));
         $runstat .= "<tr><th>Estimated End date/time</th><td>" . $ectime  . "</td></tr>";
     
     
     } 
 }
    $runstat .= "</table>";
 
 
 


 

 
 
 
 ?>
 
<html>
	<head>
		<title>Production Review (<?php echo $schedstat;?>)</title>
			<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
<script src="//code.jquery.com/jquery-3.1.0.slim.min.js"></script>		


  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="prod3.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    
	
    
	
	
	
	
	<script src="../jqb/External/jquery/jquery.js" type="text/javascript"></script>
    <script src="../jqb/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../jqb/jtable/jquery.jtable.js" type="text/javascript"></script>
    
  

    <script type="text/javascript"
            src="../jqb/jqconfirm/js/jquery-confirm.js"></script>
    

    
	
<style>
.warning{
	color:Black;
}
</style>
  
	
	
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">UP Paper LLC.</a>
    </div>
  <!--   <ul class="nav navbar-nav">
      <li class="active"><a href="#">Home</a></li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
    </ul>  -->
    <button class="btn btn-danger navbar-btn" onclick = "closeRun(<?php echo $mr;?>)">Close Run </button>
    
  </div>
</nav>



  
<div class="container-fluid text-Center">    
  <div class="row content">

    <div class="col-sm-10 text-center"> 
    <p>Order information for Mill run <?php echo $mr;?></p>
      <h3>Schedule Detail (<?php echo number_format($xwgt,2);?> % complete by weight)<br>
      Current Status: <?php echo trim($schedstat) ;?><br>
      <?php  echo $ztime;?><br>
      Estimated Completion date/time is <?php  echo $endtime;?></h3>
       <hr>
    <?php echo $ordlist;?>
    </div>
 
    <div class="col-sm-2 ">
    <div class="panel-group" id="accordion">
     <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Ship To Customer/s</a>
        </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
         <?php echo $custlist;?>
      </div>
    </div>
       <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Bill To Customer/s</a>
        </h4>
        </div>
        <div id="collapse2" class="panel-collapse collapse">
         <?php echo $biltolist;?>
      </div>
    </div>
         <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Run Status</a>
        </h4>
        </div>
        <div id="collapse3" class="panel-collapse collapse">
         <?php echo $runstat;?>
      </div>
    </div>
      
    </div>
 
   </div>

<footer class="container-fluid text-center">
  <?php echo "Current User: $user";?>
</footer>
 <upmenu class="floating-menu">
   <div class="dropdown">
  <button class="dropbtn">UP Functions</button>
  <div class="dropdown-content">
    <?php  echo $menu;?>
  </div>
</div>
  </upmenu>

	</body>
</html>

    <script>


    
function closeRun(mr){
<?php 
$s = "Update Prod_Schedule set SCHEDSTAT = 'Complete' where MILRUN = $mr  with NC";
$r = db2_exec($con, $s);

?>
window.close();
parent.location.reload();
}
    </script>	
