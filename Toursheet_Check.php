<?php
include "../UPHead.php";

$sl = "select * from toursheet_time where timeid  = 22 and eventdate >= '2018-01-01'";
$rl = db2_exec($con, $sl);

while ($rowl = db2_fetch_assoc($rl)){


$reelid = $rowl['REELID'];
$reel = $rowl['REEL'];
if (strlen($reel) == 1)$reel = '0'. $reel;
$reelnum = trim($reelid). trim($reel);

$note = '';

$s = "select reelid, reel, grade, trim(Reelid)|| substr(digits(reel),4,2),
(select
    trim(F_getPbyVN (trim(Reelid) ||substr(digits(reel),4,2),
        'Mill Run number' )) from prod_reel
    where eventname = (trim(reelid)||substr(digits(reel),4,2))
    fetch first 1 row only) as MR from toursheet
    where  reelid = '$reelid' and Reel = $reel";      

$r  = db2_exec($con, $s);
$row= db2_fetch_assoc($r);
$millrun = $row['MR'];

if (trim($millrun) == ''){
    $millrun = 'Not Found';
    $note = 'Mill Run number not found';
    $s2 = "SELECT DISTINCT (MILRUN) as MR FROM LPMAST WHERE
 SUBSTR(ROLLID, 1,5) = '$reelnum'  ";
     $r2=db2_exec($con, $s2);
     $pu = '';
     $pud = '';
     while ($row2 = db2_fetch_assoc($r2)){
    $pu .= $pud.$row2['MR'];
    $pud = '/';
}
if (trim($pu) !== ''){
    $note .= ' Produced Under ' . $pu;
}
}


$s = "Select (Select trim(F_getPbyVN ('$reelnum','Mx Grade Code')) 
from Prod_Reel where eventname = '$reelnum' fetch first 1 row only) as Prod_Grade
from toursheet  where  reelid >='$reelid' and reel = $reel";
$r = db2_exec($con, $s);
$row =db2_fetch_assoc($r);
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
 $row =db2_fetch_assoc($r);
$grade = $row['PROD_GRADE'];
if (trim($grade) == '') {
    $grade = 'Not Found';
    $note .= " <br> Grade parts 1, 4 not found in Prod_Reel";
}


$s = "Select (Select trim(F_getPbyVN ('$reelnum','Basis Weight F'))
from Prod_Reel where eventname = '$reelnum' fetch first 1 row only) as Prod_BSWGT
from toursheet  where  reelid >='$reelid' and reel = $reel";
$r = db2_exec($con, $s);
$row =db2_fetch_assoc($r);
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
//if (trim($note) !== ''){
    $url = "http://10.6.1.11/IntraNet/UpPaper/Prod_Reel_Load.php?DB=PR&REEL=$reelnum";
    ?>
    <script>
    window.open('<?php echo $url;?>','_blank');
    </script>
    
    <?php 
 echo "....... Clearing Toursheet Records .....";   
    $s = "delete from toursheet where reelid = '$reelid'  and Reel = $reel with NC ";
    $r = db2_exec($con, $s);
    
    $s = "delete from toursheet_time f1 where not exists              
(Select * from toursheet f2 where f1.sheetid = f2.sheetid)  ";
    $r = db2_exec($con, $s);
    $s = "delete from toursheet_team f1 where not exists              
(Select * from toursheet f2 where f1.sheetid = f2.sheetid)  ";
    $r = db2_exec($con, $s);
//}
echo '</p>';

}