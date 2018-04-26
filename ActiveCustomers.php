
 
<html>
	<head>
		<title>Active Customers List</title>
			<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="//code.jquery.com/jquery-3.1.0.slim.min.js"></script>		

 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="prod3.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    
	
    
	
	
	
	
	<script src="../jqb/External/jquery/jquery.js" type="text/javascript"></script>
    <script src="../jqb/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../jqb/jtable/jquery.jtable.js" type="text/javascript"></script>
    
  

    <script type="text/javascript"
            src="../jqb/jqconfirm/js/jquery-confirm.js"></script>
   <style>
th, td {
    padding: 4px;
}
.num{
	text-align: right;
}
</style> 
  
</head>
<body>





  
<div>


<?php
require_once '../uphead.php';
require "UPClass2.php";
$x = UPClass2::getUserData($con);
$sl = $x['SL'];


$year = date('y');
$year3 = (int)$year - 1;
$year4 = (int)$year - 2;


$s = "Select distinct custsh, cust#, cmcna1, cmccty, cmst, cmzip from cust_sales_ytd where cmcna1 is not null ";
$r = db2_exec($con, $s);
echo "<table border = '1' cellpadding='5'>
<tr>
<th colspan='6'>Customer </th>
<th colspan='3'>Sales for " . '20' .$year . "</th>
<th colspan='3'>Sales for " . '20' .$year3 . "</th>
<th colspan='3'>Sales for " . '20' .$year4 . "</th>
</tr>
<tr>
<th> Bill to</th>
<th> Sold to</th>
<th> Customer Name</th>
<th> City</th>
<th> State</th>
<th> zip</th>
<th> Tons</th>
<th> Invoiced</th>
<th> Commisions</th>
<th> Tons</th>
<th> Invoiced</th>
<th> Commisions</th>
<th> Tons</th>
<th> Invoiced</th>
<th> Commisions</th>
</tr>
";
while($row = db2_fetch_assoc($r)){
    
    
    $cust = $row['CUSTSH'];
    $s2 = "Select * from  cust_sales_ytd where custsh = $cust and iyear = $year";
    $r2 = db2_exec($con, $s2);
  //  var_dump($s2, db2_stmt_errormsg());
    $row2 = db2_fetch_assoc($r2);
    
    $s3 = "Select * from  cust_sales_ytd where custsh = $cust and iyear = $year3";
    $r3 = db2_exec($con, $s3);
    //  var_dump($s2, db2_stmt_errormsg());
    $row3 = db2_fetch_assoc($r3);
    
    $s4 = "Select * from  cust_sales_ytd where custsh = $cust and iyear = $year4";
    $r4 = db2_exec($con, $s4);
    //  var_dump($s2, db2_stmt_errormsg());
    $row4 = db2_fetch_assoc($r4);
    $custurl = 'CustomerDetail.php?CUST=' . $row['CUSTSH'];
    $billurl = 'CustomerDetail.php?CUST=' . $row['CUST#'];

    
    echo "<tr>";
    echo "<td><a href='$billurl' target='custDetail'>" . $row['CUST#'] . '</td>';
    echo "<td><a href='$custurl' target='custDetail'>" .  $row['CUSTSH'] . '</td>';
    echo "<td>" . $row['CMCNA1'] . '</td>';
    echo "<td>" . $row['CMCCTY'] . '</td>';
    echo "<td>" . $row['CMST'] . '</td>';
    echo "<td>" . $row['CMZIP'] . '</td>';
    if ($sl >= 800){
    echo "<td class='num'>" . number_format($row2['TONSHP'],2) . '</td>';
    echo "<td class='num'>" . number_format($row2['INVOICED'],2) . '</td>';
    echo "<td class='num'>" . number_format($row2['COMMIS'],2) . '</td>';
    echo "<td class='num'>" . number_format($row3['TONSHP'],2) . '</td>';
    echo "<td class='num'>" . number_format($row3['INVOICED'],2) . '</td>';
    echo "<td class='num'>" . number_format($row3['COMMIS'],2) . '</td>';
    echo "<td class='num'>" . number_format($row4['TONSHP'],2) . '</td>';
    echo "<td class='num'>" . number_format($row4['INVOICED'],2) . '</td>';
    echo "<td class='num'>" . number_format($row4['COMMIS'],2) . '</td>';
    } else {
        echo "<td class='num'>" . number_format($row2['TONSHP'],2) . '</td>';
        echo "<td colspan = '2'>&nbsp</td>";
        echo "<td class='num'>" . number_format($row3['TONSHP'],2) . '</td>';
        echo "<td colspan = '2'>&nbsp</td>";
        echo "<td class='num'>" . number_format($row4['TONSHP'],2) . '</td>';
        echo "<td colspan = '2'>&nbsp</td>";
    }
    echo "</tr>";
    
}
echo "</table>";

?>
</div></body></html>
