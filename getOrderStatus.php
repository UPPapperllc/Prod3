<?php
require_once '../uphead.php';
require "UPClass2.php";

if (! isset($_COOKIE['PD'])) {
    setcookie('PD', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['W1'])) {
    setcookie('W1', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['W2'])) {
    setcookie('W2', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['W3'])) {
    setcookie('W3', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['W4'])) {
    setcookie('W4', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['R1'])) {
    setcookie('R1', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['R2'])) {
    setcookie('R2', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['R3'])) {
    setcookie('R3', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['R4'])) {
    setcookie('R4', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['R5'])) {
    setcookie('R5', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['R6'])) {
    setcookie('R6', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day
if (! isset($_COOKIE['R7'])) {
    setcookie('R7', 'Y', time() + (86400 * 30), "/");
} // 86400 = 1 day




$pdv = $_COOKIE['PD'];
if ($pdv == 'Y')
    $pdx = 'checked';
else
    $pdx = '';

$w1v = $_COOKIE['W1'];
if ($w1v == 'Y')
    $w1x = 'checked';
else
    $w1x = '';

$w2v = $_COOKIE['W2'];
if ($w2v == 'Y')
    $w2x = 'checked';
else
    $w2x = '';

$w3v = $_COOKIE['W3'];
if ($w3v == 'Y')
    $w3x = 'checked';
else
    $w3x = '';

$w4v = $_COOKIE['W4'];
if ($w4v == 'Y')
    $w4x = 'checked';
else
    $w4x = '';

$r1v = $_COOKIE['R1'];
if ($r1v == 'Y')
    $r1x = 'checked';
else
    $r1x = '';

$r2v = $_COOKIE['R2'];
if ($r2v == 'Y')
    $r2x = 'checked';
else
    $r2x = '';

$r3v = $_COOKIE['R3'];
if ($r3v == 'Y')
    $r3x = 'checked';
else
    $r3x = '';

$r4v = $_COOKIE['R4'];
if ($r4v == 'Y')
    $r4x = 'checked';
else
    $r4x = '';

$r5v = $_COOKIE['R5'];
if ($r5v == 'Y')
    $r5x = 'checked';
else
    $r5x = '';

$r6v = $_COOKIE['R6'];
if ($r6v == 'Y')
    $r6x = 'checked';
else
    $r6x = '';
    $r7v = $_COOKIE['R7'];
    if ($r7v == 'Y')
        $r7x = 'checked';
        else
            $r7x = '';

$cond = " Where f1.scode not in  ('I', 'X') and cust# = 151616 and RYEAR > 0 and custsh != 319025 and year >=17";

$s = "select f1.*, f2.*, f3.*, f6.*,f7.*,                       
SUBSTR(f2.GRD8, 1,2) || '#' as grade,                           
cast(('20' || Year || '-' ||  Month || '-' || Day) as Date) as  
 OrdDate,                                                       
cast(('20' ||RYear || '-' || RMonth || '-' ||RDay) as Date) as  
ReqDate,                                                        
(cast(f1.rolwth as char(3)) || f1.rolfrc) as RollWth,           
   days(                                                        
cast(('20' ||RYear || '-' || RMonth || '-' ||RDay) as Date)) -  
Days(char(current date)) as daytildue                           
 from ordatep f1                                                
left join ordentp f2 on f1.milord = f2.milord                   
left join hdcust f3 on cmcust = custsh     
left join transittime f7 on custsh = cust                     
                                            
              
left join shpsubp f6 on F1.milord = f6.milord 
 and f1.ship# = f6.ship#  $cond 
order by cast(('20' ||RYear || '-' || RMonth || '-' ||RDay) as Date), f1.milord";

$r = db2_exec($con, $s);

if (! $r) {
    var_dump($s, db2_stmt_errormsg());
}

$prodrolls = 0;
$prodnotInv = 0;
$notProduced = 0;
$openlines = 0;
$overdue = 0;
$due1 = 0;
$due2 = 0;
$due3 = 0;
$other = 0;
$duetoday = 0;
$partial = 0;
$npScheduled = 0;
$nxScheduled = 0;
$ordlist = '<table border = "1" class="table table-sm >
<tr style="display:block;  position: fixed;">
<th>Ship to Number</th>
<th>Customer Name</th>
<th>City</th>
<th>State</th>
<th>Order</th>
<th>Ship#</th>
<th>Grade</th>
<th>Cust<br> PO</th>
<th>Order<br> Date</th>
<th>Required<br> by Date</th>
<th>Roll<br> Width</th>
<th>Mill<br> Run</th>
<th>Sched <br>Date</th>
<th>Ship<br>Via</th>
<th>Transit<br>Miles</th>
<th>Transit<br>Days</th>
<th>Pick Up Date</th>
<th>Days Until Due</th>
<th>Confirmed <br>Del Date</th>
<th>Rolls <br>Produced</th>
<th>Rolls <br>Shipped</th>
<th>Rolls <br>Requested</th>
<th>Age</th>
<th>Rpt Status</th>
</tr>';

While ($row = db2_fetch_assoc($r)) {
    $ord = $row['MILORD'];
    $shp = $row['SHIP#'];
    $wl = true;
    
    $transitmiles = $row['TRUCKMILES'];
    $truckdays = $row['TRUCKDAYS'];
    $raildays = $row['RAILDAYS'];
    if (trim($row['CARVIA']) == 'R')
        $transitdays = $raildays;
    else
        $transitdays = $truckdays;
    
    $s2 = "Select * from milposp where milord = '$ord' and SHIP# = $shp fetch first 1 row only";
    $r2 = db2_exec($con, $s2);
    if (! $r2) {
        $schdate = ' ';
        $mr = '';
        
        var_dump($s2, db2_stmt_errormsg());
    } else {
        $row2 = db2_fetch_assoc($r2);
        $mr = $row2['MILRN#'];
        
        if (trim($mr) !== '') {
            $s3 = "Select * from Prod_Schedule where milrun = $mr";
            $r3 = db2_exec($con, $s3);
            $row3 = db2_fetch_assoc($r3);
            $schdate = $row3['SCHDATE'];
        } else {
            $schdate = ' ';
            $mr = '';
        }
    }
    
    $srolls = 0;
    $prolls = 0;
    $s3 = "Select count(*) as CNT from LPMAST where milord = '$ord' and SHIPNUM = $shp and lpstat not in ('Culled', 'Deleted')";
    $r3 = db2_exec($con, $s3);
    // var_dump($s3, db2_stmt_errormsg());
    $row3 = db2_fetch_assoc($r3);
    $prolls = $row3['CNT'];
    $s4 = "Select count(*) as CNT from LPMAST where milord = '$ord' and SHIPNUM = $shp and lpstat  in ('Shipped', 'Billed')";
    $r4 = db2_exec($con, $s4);
    $row4 = db2_fetch_assoc($r4);
    $srolls = $row4['CNT'];
    $rolls = $row['ROLLS#'];
    $rowclass = $rowclass = ' ';
    // echo "<br>Produced: $prolls, Shipped: $srolls ";
    
    // Produced and shipped
    if ((int) $prolls >= (int) 0 and (int) $srolls >= (int) $prolls and (int) $prolls == (int) $row['ROLLS#']) {
        $rowclass = 'style = "Background-color:green"';
        $prodrolls += 1;
        $rstat = 'Cmp/opn';
        if ($r1v == 'N')
            $wl = false;
    } elseif ((int) $prolls >= (int) 0 and (int) $srolls >= (int) $prolls and (int) $prolls !== (int) $row['ROLLS#'] and (int) $prolls !== 0) {
        $rowclass = 'style = "Background-color:redorange; color: greenyellow;"';
        $partial += 1;
        $rstat = 'Prtl/Prod';
        if ($r2v == 'N')
            $wl = false;
    } elseif ((int) $prolls !== (int) 0 and (int) $srolls == (int) 0) {
        $rowclass = 'style = "Background-color:MediumOrchid "';
        $prodnotInv += 1;
        $rstat = 'Prd/N/Inv'; // Produced not invoiced
        if ($r3v == 'N')
            $wl = false;
    } elseif ((int) $prolls == (int) 0 and (int) $srolls !== (int) 0) {
        $rowclass = 'style = "Background-color:pink"';
        $otherb += 1;
        $rstat = 'Shp/N/Prod'; // Shipped not produced
        if ($r4v == 'N')
            $wl = false;
    } elseif ((int) $prolls == (int) 0 and (int) $srolls == (int) 0 and trim($mr) == '') {
        $rowclass = ' ';
        $notProduced += 1;
        $rstat = 'Open';
        if ($r5v == 'N')
            $wl = false;
    } elseif ((int) $prolls == (int) 0 and (int) $srolls == (int) 0 and trim($mr) !== '' and trim($schdate) !== '') {
       
            $npScheduled += 1;
            $rstat = 'Sched';
            if ($r6v == 'N')       $wl = false;
      
        }
        elseif ((int) $prolls == (int) 0 and (int) $srolls == (int) 0 and trim($mr) !== '' and trim($schdate) == '') {
            
            $nxScheduled += 1;
            $rstat = 'Sched/N/Dte';
            if ($r7v == 'N')       $wl = false;
            
        }
    
    $tdstyle = '';
    $openlines += 1;
    // echo "<br>dtd : " . (int)$row['DAYTILDUE'] ;
    if ((int) $row['DAYTILDUE'] < (int) 0) {
        $overdue += 1;
        $tdstyle = "style = 'color: #FFFF00'";
        $age = 'Past'; // past due
        if ($pdv == 'N')
            $wl = false;
    } elseif ((int) $row['DAYTILDUE'] == (int) 0) {
        $duetoday += 1;
        $tdstyle = "style = 'color: #FFE773'";
        $age = 'Due'; // due today
        if ($w1v == 'N')
            $w4 = false;
    } elseif ((int) $row['DAYTILDUE'] > (int) 0 and (int) $row['DAYTILDUE'] <= (int) 7) {
        $due1 += 1;
        $tdstyle = "style = 'color: green'";
        $age = 'W1'; // Week 1
        if ($w2v == 'N')
            $wl = false;
    } elseif ((int) $row['DAYTILDUE'] > (int) 7 and (int) $row['DAYTILDUE'] <= (int) 14) {
        $due2 += 1;
        $tdstyle = "style = 'color: turquoise'";
        $age = 'W2';
        if ($w3v == 'N')
            $wl = false;
    } elseif ((int) $row['DAYTILDUE'] > (int) 14) {
        $due3 += 1;
        $tdstyle = "style = 'color: greenyellow'";
        $age = '++';
        if ($w3v == 'N')
            $wl = false;
    }
    
    if ($wl) {
        $custurl = 'CustomerDetail.php?CUST=' . $row['CUSTSH']; 
        $ordlist .= "<tr $rowclass>";
        $ordlist .= "<td><a href='$custurl' target='custDetail'>" .  $row['CUSTSH'] . '</td>';
        $ordlist .= "<td>" . $row['CMCNA1'] . '</td>';
        $ordlist .= "<td>" . $row['CMCCTY'] . '</td>';
        $ordlist .= "<td>" . $row['CMST'] . '</td>';
        $detailurl = 'OrderDetail.php?O=' . $row['MILORD'] . '&S=' . $row['SHIP#'];
        $ordlist .= "<td><a href='$detailurl' target='OrdDetail'>" . $row['MILORD'] . "</a></td>";
        // $ordlist .= "<td>" . $row['MILORD'] . '</td>';
        $ordlist .= "<td>" . $row['SHIP#'] . '</td>';
        $ordlist .= "<td>" . $row['GRADE'] . '</td>';
        $ordlist .= "<td>" . $row['CUPO15'] . '</td>';
        $ordlist .= "<td>" . $row['ORDDATE'] . '</td>';
        $ordlist .= "<td $tdstyle>" . $row['REQDATE'] . '</td>';
        $ordlist .= "<td>" . $row['ROLLWTH'] . '</td>';
        $ordlist .= "<td>" . $mr . '</td>';
        $ordlist .= "<td>" . $schdate . '</td>';
        $ordlist .= "<td>" . $row['CARVIA'] . '</td>';
        $ordlist .= "<td>" . $transitmiles . '</td>';
        $ordlist .= "<td>" . $transitdays . '</td>';
        $ordlist .= "<td>" . '&nbsp' . '</td>';
        $ordlist .= "<td>" . $row['DAYTILDUE'] . '</td>';
        $ordlist .= "<td>" . $row['DELDTAP'] . '</td>';
        $ordlist .= "<td>" . number_format($prolls) . '</td>';
        $ordlist .= "<td>" . number_format($srolls) . '</td>';
        $ordlist .= "<td>" . $row['ROLLS#'] . '</td>';
        $ordlist .= "<td>" . $age . '</td>';
        $ordlist .= "<td>" . $rstat . '</td>';
        $ordlist .= "</tr>";
    }
}

$ordlist .= "</table>";
echo "<div class='newspaper' height='300'>

<div colspan='3'>Open Lines: $openlines</div>

<input type=checkbox id='PD' onchange='setCookie(this)'  $pdx/><divd width='100px' align='right' style = 'color: red'>Past Due: $overdue</divd> <br>
<input type=checkbox id='W1' onchange='setCookie(this)'  $w1x/>Next 7 Days: <divd width='100px' align='right' style = 'color: green'>$due1</divd> <br>
<input type=checkbox id='W2' onchange='setCookie(this)'  $w2x/>8 to 14 days: <divd width='100px' align='right' style = 'color: turquoise'>$due2</divd></br>
<input type=checkbox id='W3' onchange='setCookie(this)'  $w3x/>Older than 14 days: <divd width='100px' align='right' style = 'color: greenyellow'>$due3</divd><br>
<input type=checkbox id='W4' onchange='setCookie(this)'  $w4x/>Due Today: <divd width='100px' align='right' style = 'color: redorange'>$duetoday



<input type=checkbox id='R1' onchange='setCookie(this)'  $r1x/><divd width='100px' style = 'Background-color:green' align='right'>Completed Lines still open: $prodrolls</divd><br>
<input type=checkbox id='R2' onchange='setCookie(this)'  $r2x/><divd width='100px' align='right' style = 'Background-color:redorange'>Partial: $partial</divd><br>
<input type=checkbox id='R3' onchange='setCookie(this)'  $r3x/> <divd width='100px' style = 'Background-color:MediumOrchid ' align='right'>Produced Not invoiced: $prodnotInv</divd><br>




<input type=checkbox id='R5' onchange='setCookie(this)'  $r5x/><divd width='100px' align='right' >Orders not assigned: $notProduced</divd><br>
<input type=checkbox id='R6' onchange='setCookie(this)'  $r6x/><divd>Pending Production$npScheduled </divd><br>
<input type=checkbox id='R7' onchange='setCookie(this)'  $r7x/><divd>Assigned to Production Date not Set$nxScheduled </divd><br>





</div>
";
// echo $ordlist;
?>

<html>
<head>
<title>Order Status Report)</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="//code.jquery.com/jquery-3.1.0.slim.min.js"></script>

<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="prod3.css">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>








<script src="../jqb/External/jquery/jquery.js" type="text/javascript"></script>
<script src="../jqb/jquery-ui.min.js" type="text/javascript"></script>
<script src="../jqb/jtable/jquery.jtable.js" type="text/javascript"></script>



<script type="text/javascript"
	src="../jqb/jqconfirm/js/jquery-confirm.js"></script>




<script>
var windowName = 'userMenu'; 
var viewportwidth = window.innerWidth;
var viewportheight = document.documentElement.clientHeight;
window.resizeBy(-200,0);
//window.moveTo(0,0);





function setCookie(inval){
	console.log('Set Cookie: ', inval.id, inval.checked);
	if (inval.checked){
		document.cookie = inval.id + "=Y";
} else {
	document.cookie = inval.id + "=N";
}
	location.reload();
}

</script>

<style>

/* unvisited link */
a:link {
    color: red;
}

/* visited link */
a:visited {
    color: green;
}

/* mouse over link */
a:hover {
    color: hotpink;
}

/* selected link */
a:active {
    color: yellow;
}

table tbody, table thead
{
    display: block;
}
table thead 
{
   overflow: auto;
   height: 100px;
}
table tbody 
{
   overflow: auto;
   height: 80%;
}

.newspaper {
    -webkit-column-count: 3; /* Chrome, Safari, Opera */
    -moz-column-count: 3; /* Firefox */
    column-count: 3;
    -webkit-column-gap: 40px; /* Chrome, Safari, Opera */
    -moz-column-gap: 40px; /* Firefox */
    column-gap: 40px;
    -webkit-column-rule-style: solid; /* Chrome, Safari, Opera */
    -moz-column-rule-style: solid; /* Firefox */
    column-rule-style: solid;
	height: 20%;
}


</style>

</head>
<body>






	<div>
<?php echo $ordlist;?>    

   </div>

	<footer class="container-fluid text-center"> </footer>


</body>
</html>