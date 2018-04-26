<?php
include '../uphead.php';

$s = "select * from hdcust where not exists                          
(Select * from transittime where cust = cmcust) 
                                           ";
$r = db2_exec($con, $s);
while ($row2 = db2_fetch_assoc($r)){
    $cust = $row2['CMCUST'];
    
   // var_dump($s2, db2_stmt_errormsg());
   
    echo "<br> Loading $cust " . $row2['CMZIP'] . ' ' . $row2['CMCCTY'] . ' ' . $row2['CMST'];
    ?>
     <script>
var url = "DistanceAPI.php?CUST=<?php echo $cust;?>";
window.open(src=url, '<?php echo 'SD' . $cust;?>', "width=400,height=100");
     </script>
     
     <?php 
     
 }