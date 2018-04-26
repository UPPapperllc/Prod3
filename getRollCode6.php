<?php
require_once '../uphead.php';



$rows=array();


$s= "SELECT scode, substr(grd8,1,2) || '-' || substr(grd8,11,2) as grade,
sum(ROLWGT) as WGT, count(*) as CNT FROM whsinvV3 WHERE                           
WHSINVV3.SCODE <> 'Z' GROUP BY scode, grd8                          
order by scode, grd8                                                ";
$r = db2_exec($con, $s);


while($row = db2_fetch_assoc($r)){
    if ($row['SCODE'] == 'B') $x['SCODE'] = 'Not Invoiced(B)';
    if ($row['SCODE'] == 'W') $x['SCODE'] = 'Returned(W)';
    if ($row['SCODE'] == 'P') $x['SCODE'] = 'Produced(P)';
    if (trim($row['SCODE']) == 'S') $x['SCODE'] = 'Roll List(S)';
    if ($row['SCODE'] == 'I') $x['SCODE'] = 'Cat/Dog(I)';
    if ($row['SCODE'] == 'A') $x['SCODE'] = 'BoL Called(A)';
    $x['GRADE'] = $row['GRADE'];
    $x['WGT'] = number_format($row['WGT']);
    $x['CNT'] = number_format($row['CNT']);
    
    $rows[] = $x;
//SCODE, GRADE, WGT, CNT
}


$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $rows;

//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);



function setRow($con, $offset){
    
    
    
    
    
    $data['RECID'] = $offset;
    
    $d0Y =  date("Y", time() - (86400 * $offset));
    $d0M =  date("m", time() - (86400 * $offset));
    $d0D =  date("d", time() - (86400 * $offset));
    
    $mid = getMid($d0Y, $d0M, $d0D);
    $m = $mid . $d0D;
    $dm = date("m/d", time() - (86400 * $offset));
    
    $data['MD'] = $dm;
    $data['Y'] = $d0Y;
    $data['M'] = $d0M;
    $data['D'] = $d0D;
    $data['MID'] = $m;
    
    
    
    $s = "SELECT  count(*) as CNT, sum(rolwgta) as SUM FROM lpmast
    WHERE substr(rollid,1,3) = '$m'
    and  lpstat  <> 'Culled'                                   ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['Wgt'] = number_format($row['SUM']);
    $data['Rolls'] = number_format($row['CNT'],0);
    $data['Tons'] = round($row['SUM']/2000,0);
    
    
    $s2 = "SELECT  count(*) as CNT, sum(rolwgta) as SUM FROM lpmast
    WHERE substr(rollid,1,3) = '$m'
    and  lpstat  = 'Culled'                                   ";
    $r2 = db2_exec($con, $s2);
    $row2 = db2_fetch_assoc($r2);
    
    $data['CWgt'] = number_format($row2['SUM'],0);
    $data['CRolls'] = number_format($row2['CNT'],0);
    $data['CTons'] = round($row2['SUM']/2000,0);
    
    
    return $data;
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



