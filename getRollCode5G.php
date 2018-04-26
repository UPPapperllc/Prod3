<?php
require_once '../uphead.php';
if (isset($_GET['OCC'])) $occ  = $_GET['OCC']; else $occ = 16;

//$oc = 16;
$row=array();

$label = array();
$data1 = array();
$data2=array();

$os = $occ;
$ttl = 0;
$jTableResult = array();
//while($os <= $oc){
    $rtn = setRow($con, $os - 2000,0);
    $data1 = array($rtn['JanInv'], $rtn['FebInv'], $rtn['MrcInv'], $rtn['AprInv'], $rtn['MayInv'], $rtn['JunInv'], $rtn['JulInv'], $rtn['AugInv'] , $rtn['SepInv'], $rtn['OctInv'],$rtn['NovInv'], $rtn['DecInv'] );
    $jTableResult['Y1'] = $os;
  $os += 1;
    $rtn = setRow($con, $os - 2000,0);
    $data2 = array($rtn['JanInv'], $rtn['FebInv'], $rtn['MrcInv'], $rtn['AprInv'], $rtn['MayInv'], $rtn['JunInv'], $rtn['JulInv'], $rtn['AugInv'] , $rtn['SepInv'], $rtn['OctInv'],$rtn['NovInv'], $rtn['DecInv'] );
    $jTableResult['Y2'] = $os;
    $os += 1;
    $rtn = setRow($con, $os - 2000,0);
    $data3 = array($rtn['JanInv'], $rtn['FebInv'], $rtn['MrcInv'], $rtn['AprInv'], $rtn['MayInv'], $rtn['JunInv'], $rtn['JulInv'], $rtn['AugInv'] , $rtn['SepInv'], $rtn['OctInv'],$rtn['NovInv'], $rtn['DecInv'] );
    $jTableResult['Y3'] = $os;
    $os += 1;

    $datay = setAvr($data1, $data2, $data3);
    $datax =  $datay['nextyear'];
    $data5 = array($datax['avr1'], $datax['avr2'], $datax['avr3'],$datax['avr4'], $datax['avr5'],$datax['avr6'],$datax['avr7'],$datax['avr8'],$datax['avr9'],$datax['avr10'],$datax['avr11'],$datax['avr12']);
    
    $jTableResult['Y5'] = $os;
    $datax = $datay['cyear'];
    $data4 = array($datax['avr1'], $datax['avr2'], $datax['avr3'],$datax['avr4'], $datax['avr5'],$datax['avr6'],$datax['avr7'],$datax['avr8'],$datax['avr9'],$datax['avr10'],$datax['avr11'],$datax['avr12']);
    $jTableResult['Y4'] = 'Predictive';
    $data6 =  $datay['avr'];
    $jTableResult['Y6'] = 'Avg Growth';
    
    
$label[] = 'Jan';
$label[] = 'Feb';
$label[] = 'March';
$label[] = 'April';
$label[] = 'May';
$label[] = 'June';
$label[] = 'July';
$label[] = 'Aug';
$label[] = 'Sep';
$label[] = 'Oct';
$label[] = 'Nov';
$label[] = 'Dec';

$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data1;
$jTableResult['Labels'] = $label;
$jTableResult['Data2'] = $data2;
$jTableResult['Data1'] = $data1;
$jTableResult['Data3'] = $data3;
$jTableResult['Data4'] = $data4;
$jTableResult['Data5'] = $data5;
$jTableResult['Data6'] = $data6;
//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);



function setRow($con, $yr, $set){
    
    
    $ttlvar = 0;
    $var1  = 0;
    $var2  = 0;
    $var3  = 0;
    $var4  = 0;
    $var5  = 0;
    $var6  = 0;
    $var7  = 0;
    $var8  = 0;
    $var9  = 0;
    $var10  = 0;
    $var11  = 0;
    $var12  = 0;
    
    $data['RECID'] = $yr;
    $data['MD'] = $yr;
    $data1['MD'] = $yr;
    $data2['MD'] = $yr;
    $pyr = $yr - 1;
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $pyr and
    IMONTH= 12 ";
    $r = db2_exec($con, $s);
    //  var_dump($s, db2_stmt_errormsg());
    $rowp = db2_fetch_assoc($r);
    
    $s = "SELECT  sum(INVGRS) as INV FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 1 ";
    $r = db2_exec($con, $s);
    //  var_dump($s, db2_stmt_errormsg());
    $row1 = db2_fetch_assoc($r);
    $data['JanInv'] = (float)number_format($row1['INV'] / 1000000,2);
  //  $data['JanCom'] = number_format($row1['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 2 ";
    $r = db2_exec($con, $s);
    $row2 = db2_fetch_assoc($r);
    $data['FebInv'] = (float)number_format($row2['INV'] / 1000000,2);
   // $data['FebCom'] = number_format($row2['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 3 ";
    $r = db2_exec($con, $s);
    $row3 = db2_fetch_assoc($r);
    $data['MrcInv'] = (float)number_format($row3['INV'] / 1000000,2);
  //  $data['MrcCom'] = number_format($row3['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 4 ";
    $r = db2_exec($con, $s);
    $row4 = db2_fetch_assoc($r);
    $data['AprInv'] = (float)number_format($row4['INV'] / 1000000,2);
  //  $data['AprCom'] = number_format($row4['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 5 ";
    $r = db2_exec($con, $s);
    $row5 = db2_fetch_assoc($r);
    $data['MayInv'] = (float)number_format($row5['INV'] / 1000000,2);
  //  $data['MayCom'] = number_format($row5['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 6 ";
    $r = db2_exec($con, $s);
    $row6 = db2_fetch_assoc($r);
    $data['JunInv'] = (float)number_format($row6['INV'] / 1000000,2);
  //  $data['JunCom'] = number_format($row6['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 7 ";
    $r = db2_exec($con, $s);
    $row7 = db2_fetch_assoc($r);
    $data['JulInv'] = (float)number_format($row7['INV'] / 1000000,2);
  //  $data['JulCom'] = number_format($row7['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 8 ";
    $r = db2_exec($con, $s);
    $row8 = db2_fetch_assoc($r);
    $data['AugInv'] = (float)number_format($row8['INV'] / 1000000,2);
  //  $data['AugCom'] = number_format($row8['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 9 ";
    $r = db2_exec($con, $s);
    $row9 = db2_fetch_assoc($r);
    $data['SepInv'] = (float)number_format($row9['INV'] / 1000000,2);
  //  $data['SepCom'] = number_format($row9['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 10 ";
    $r = db2_exec($con, $s);
    $row10 = db2_fetch_assoc($r);
    $data['OctInv'] = (float)number_format($row10['INV'] / 1000000,2);
  //  $data['OctCom'] = number_format($row10['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 11 ";
    $r = db2_exec($con, $s);
    $row11 = db2_fetch_assoc($r);
    $data['NovInv'] = (float)number_format($row11['INV'] / 1000000,2);
 //   $data['NovCom'] = number_format($row11['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 12 ";
    $r = db2_exec($con, $s);
    $row12 = db2_fetch_assoc($r);
    $data['DecInv'] = (float)number_format($row12['INV'] / 1000000,2);
 //   $data['DecCom'] = number_format($row12['COMM']);
    $vcnt = 0;
    if (trim($rowp['INV']) !== '' and (float)$rowp['INV'] !== 0){
        if (trim($row1['INV']) !== '' and (float)$row1['INV'] !== 0){
            $ttlvar += floatval($row1['INV'] - $rowp['INV']);
            $var1 += floatval($row1['INV'] - $rowp['INV']);
            $vcnt++;
        }
    }
    if (trim($row1['INV']) !== '' and (float)$row1['INV'] !== 0){
        if (trim($row2['INV']) !== '' and (float)$row2['INV'] !== 0){
            $ttlvar += floatval($row2['INV'] - $row1['INV']);
            $var2 += floatval($row2['INV'] - $row1['INV']);
            $vcnt++;
        }
    }
    if (trim($row2['INV']) !== '' and (float)$row2['INV'] !== 0){
        if (trim($row3['INV']) !== '' and (float)$row3['INV'] !== 0){
            $ttlvar += floatval($row3['INV'] - $row2['INV']);
            $var3 += floatval($row3['INV'] - $row2['INV']);
            $vcnt++;
        }
    }
    if (trim($row3['INV']) !== '' and (float)$row3['INV'] !== 0){
        if (trim($row4['INV']) !== '' and (float)$row4['INV'] !== 0){
            $ttlvar += floatval($row4['INV'] - $row3['INV']);
            $var4 += floatval($row4['INV'] - $row3['INV']);
            $vcnt++;
        }
    }
    
    if (trim($row4['INV']) !== '' and (float)$row4['INV'] !== 0){
        if (trim($row5['INV']) !== '' and (float)$row5['INV'] !== 0){
            $ttlvar += floatval($row5['INV'] - $row4['INV']);
            $var5 += floatval($row5['INV'] - $row4['INV']);
            $vcnt++;
        }
    }
    if (trim($row5['INV']) !== '' and (float)$row5['INV'] !== 0){
        if (trim($row6['INV']) !== '' and (float)$row6['INV'] !== 0){
            $ttlvar += floatval($row6['INV'] - $row5['INV']);
            $var6 += floatval($row6['INV'] - $row5['INV']);
            $vcnt++;
        }
    }
    if (trim($row6['INV']) !== '' and (float)$row6['INV'] !== 0){
        if (trim($row7['INV']) !== '' and (float)$row7['INV'] !== 0){
            $ttlvar += floatval($row7['INV'] - $row6['INV']);
            $var7 += floatval($row7['INV'] - $row6['INV']);
            $vcnt++;
        }
    }
    
    if (trim($row7['INV']) !== '' and (float)$row7['INV'] !== 0){
        if (trim($row8['INV']) !== '' and (float)$row8['INV'] !== 0){
            $ttlvar += floatval($row8['INV'] - $row7['INV']);
            $var8 += floatval($row8['INV'] - $row7['INV']);
            $vcnt++;
        }
    }
    
    if (trim($row8['INV']) !== '' and (float)$row8['INV'] !== 0){
        if (trim($row9['INV']) !== '' and (float)$row9['INV'] !== 0){
            $ttlvar += floatval($row9['INV'] - $row8['INV']);
            $var9 += floatval($row9['INV'] - $row8['INV']);
            $vcnt++;
        }
    }
    
    if (trim($row9['INV']) !== '' and (float)$row9['INV'] !== 0){
        if (trim($row10['INV']) !== '' and (float)$row10['INV'] !== 0){
            $ttlvar += floatval($row10['INV'] - $row9['INV']);
            $var10 += floatval($row10['INV'] - $row9['INV']);
            $vcnt++;
        }
    }
    
    if (trim($row10['INV']) !== '' and (float)$row10['INV'] !== 0){
        if (trim($row11['INV']) !== '' and (float)$row11['INV'] !== 0){
            $ttlvar += floatval($row11['INV'] - $row10['INV']);
            $var11 += floatval($row11['INV'] - $row10['INV']);
            $vcnt++;
        }
    }
    
    if (trim($row11['INV']) !== '' and (float)$row11['INV'] !== 0){
        if (trim($row12['INV']) !== '' and (float)$row12['INV'] !== 0){
            $ttlvar += floatval($row12['INV'] - $row11['INV']);
            $var12 += floatval($row12['INV'] - $row11['INV']);
            $vcnt++;
        }
    }
    
    
    $data1['var0'] = $ttlvar;
    $data1['var1'] = $var1;
    $data1['var2'] = $var2;
    $data1['var3'] = $var3;
    $data1['var4'] = $var4;
    $data1['var5'] = $var5;
    $data1['var6'] = $var6;
    $data1['var7'] = $var7;
    $data1['var8'] = $var8;
    $data1['var9'] = $var9;
    $data1['var10'] = $var10;
    $data1['var11'] = $var11;
    $data1['var12'] = $var12;
   // $data2['varavr'] = $ttlvar/$vcnt;
    
    
    if ($set == 0) return $data;
    if ($set == 1) return $data1;
    if ($set == 2) return $data2;
    
}


function setAvr($data1, $data2, $data3){
    
    // look for changes in data 1 and 2 by month
  
   $avr1 = 0;
   $avr2 = 0;
   $avr3 = 0;
   $avr4 = 0;
   $avr5 = 0;
   $avr6 = 0;
   $avr7 = 0;
   $avr8 = 0;
   $avr9 = 0;
   $avr10 = 0;
   $avr11 = 0;
   $avr12 = 0;
   $vcnt = 0;
   $ttlavr = 0;
 
   //****************************************************
   $jan1 =  $data1[0];
   $jan2 =  $data2[0];
   $jan3 =  $data3[0];
   $avra = 0;
   $avrb=0;
   if ($jan1 > 0 and $jan2>0){
       $avra = ($jan1 + $jan2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($jan2> 0 and $jan3>0){
       $avrb = ($jan2 + $jan3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($jan2> 0 and $jan3>0){
       $avrb = ($jan2 + $jan3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr1 =  number_format(($avra + $avrb)/2,2);
   }
    
   if ($avra > 0 and $avrb == 0){
       $avr1 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr1 = number_format($avrb,2);
   }
   
   //****************************************************
   $feb1 =  $data1[1];
   $feb2 =  $data2[1];
   $feb3 =  $data3[1];
   $avra = 0;
   $avrb=0;
   if ($feb1 > 0 and $feb2>0){
       $avra = ($feb1 + $feb2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($feb2> 0 and $feb3>0){
       $avrb = ($feb2 + $feb3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($feb2> 0 and $feb3>0){
       $avrb = ($feb2 + $feb3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr2 =  number_format(($avra + $avrb)/2,2);;
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr2 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr2 = number_format($avrb,2);
   }
   
   //****************************************************
 
   //****************************************************
   $mch1 =  $data1[1];
   $mch2 =  $data2[1];
   $mch3 =  $data3[1];
   $avra = 0;
   $avrb=0;
   if ($mch1 > 0 and $mch2>0){
       $avra = ($mch1 + $mch2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($mch2> 0 and $mch3>0){
       $avrb = ($mch2 + $mch3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($mch2> 0 and $mch3>0){
       $avrb = ($mch2 + $mch3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr2 =  number_format(($avra + $avrb)/2,2);;
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr2 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr2 = number_format($avrb,2);
   }
   
   
   //****************************************************
 
   $apr1 =  $data1[3];
   $apr2 =  $data2[3];
   $apr3 =  $data3[3];
   $avra = 0;
   $avrb=0;
   if ($apr1 > 0 and $apr2>0){
       $avra = ($apr1 + $apr2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($apr2> 0 and $apr3>0){
       $avrb = ($apr2 + $apr3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($apr2> 0 and $apr3>0){
       $avrb = ($apr2 + $apr3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr4 =  number_format(($avra + $avrb)/2,2);
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr4 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr4 = number_format($avrb,2);
   }
   //****************************************************
   $may1 =  $data1[4];
   $may2 =  $data2[4];
   $may3 =  $data3[4];
   $avra = 0;
   $avrb=0;
   if ($may1 > 0 and $may2>0){
       $avra = ($may1 + $may2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($may2> 0 and $may3>0){
       $avrb = ($may2 + $may3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($may2> 0 and $may3>0){
       $avrb = ($may2 + $may3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr5 =  number_format(($avra + $avrb)/2,2);
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr5 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr5 = number_format($avrb,2);
   }
   
   //****************************************************
   $jun1 =  $data1[5];
   $jun2 =  $data2[5];
   $jun3 =  $data3[5];
   $avra = 0;
   $avrb=0;
   if ($jun1 > 0 and $jun2>0){
       $avra = ($jun1 + $jun2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($jun2> 0 and $jun3>0){
       $avrb = ($jun2 + $jun3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($jun2> 0 and $jun3>0){
       $avrb = ($jun2 + $jun3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr6 =  number_format(($avra + $avrb)/2,2);
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr6 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr6 = number_format($avrb,2);
   }
   
   //****************************************************
   $jul1 =  $data1[6];
   $jul2 =  $data2[6];
   $jul3 =  $data3[6];
   $avra = 0;
   $avrb=0;
   if ($jul1 > 0 and $jul2>0){
       $avra = ($jul1 + $jul2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($jul2> 0 and $jul3>0){
       $avrb = ($jul2 + $jul3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($jul2> 0 and $jul3>0){
       $avrb = ($jul2 + $jul3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr7 =  number_format(($avra + $avrb)/2,2);
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr7 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr7 = number_format($avrb,2);
   }
   
   //****************************************************
   $aug1 =  $data1[7];
   $aug2 =  $data2[7];
   $aug3 =  $data3[7];
   $avra = 0;
   $avrb=0;
   if ($aug1 > 0 and $aug2>0){
       $avra = ($aug1 + $aug2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($aug2> 0 and $aug3>0){
       $avrb = ($aug2 + $aug3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($aug2> 0 and $aug3>0){
       $avrb = ($aug2 + $aug3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr8 =  number_format(($avra + $avrb)/2,2);
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr8 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr8 = number_format($avrb,2);
   }
   //****************************************************
   $sep1 =  $data1[8];
   $sep2 =  $data2[8];
   $sep3 =  $data3[8];
   $avra = 0;
   $avrb=0;
   if ($sep1 > 0 and $sep2>0){
       $avra = ($sep1 + $sep2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($sep2> 0 and $sep3>0){
       $avrb = ($sep2 + $sep3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($sep2> 0 and $sep3>0){
       $avrb = ($sep2 + $sep3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr9 =  number_format(($avra + $avrb)/2,2);
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr9 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr9 = number_format($avrb,2);
   }
   
   //****************************************************
   $oct1 =  $data1[9];
   $oct2 =  $data2[9];
   $oct3 =  $data3[9];
   $avra = 0;
   $avrb=0;
   if ($oct1 > 0 and $oct2>0){
       $avra = ($oct1 + $oct2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($oct2> 0 and $oct3>0){
       $avrb = ($oct2 + $oct3)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($oct2> 0 and $oct3>0){
       $avrb = ($oct2 + $oct3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr10 =  number_format(($avra + $avrb)/2,2);
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr10 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr10 = number_format($avrb,2);
   }
   //****************************************************
   $nov1 =  $data1[10];
   $nov2 =  $data2[10];
   $nov3 =  $data3[10];
   $avra = 0;
   $avrb=0;
   if ($nov1 > 0 and $nov2>0){
       $avra = ($nov1 + $nov2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($nov2> 0 and $nov3>0){
       $avrb = ($nov2 + $nov3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avra,2);
   }
   if ($nov2> 0 and $nov3>0){
       $avrb = ($nov2 + $nov3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr11 =  number_format(($avra + $avrb)/2,2);
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr11 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr11 = number_format($avrb,2);
   }
   
   //****************************************************
   $dec1 =  $data1[11];
   $dec2 =  $data2[11];
   $dec3 =  $data3[11];
   $avra = 0;
   $avrb=0;
   if ($dec1 > 0 and $dec2>0){
       $avra = ($dec1 + $dec2)/2;
       $vcnt += 1;
       $ttlavr = $avra;
   }
   if ($dec2> 0 and $dec3>0){
       $avrb = ($dec2 + $dec3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avra,2);
   }
   if ($dec2> 0 and $dec3>0){
       $avrb = ($dec2 + $dec3)/2;
       $vcnt += 1;
       $ttlavr = number_format($avrb,2);
   }
   if ($avra > 0 and $avrb>0){
       $avr12 =  number_format(($avra + $avrb)/2,2);
   }
   
   if ($avra > 0 and $avrb == 0){
       $avr12 = number_format($avra,2);
   }
   if ($avra ==   0 and $avrb > 0){
       $avr12 = number_format($avrb,2);
   }
   
   $basevr = $ttlavr/$vcnt;
   
   $avr1 = (float)($data1[11] - $data1[10]);
   $avr2 = (float)($data2[0] - $data1[11]);
   $avr3 = (float)($data2[1] - $data2[0]);
   $avr4 = (float)($data2[2] - $data2[1]);
   $avr5 = (float)($data2[3] - $data2[2]);
   $avr6 = (float)($data2[4] - $data2[3]);
   $avr7 = (float)($data2[5] - $data2[4]);
   $avr8 = (float)($data2[6] - $data2[5]);
   $avr9 = (float)($data2[7] - $data2[6]);
   $avr10 = (float)($data2[8] - $data2[7]);
   $avr11 = (float)($data2[9] - $data2[8]);
   $avr12 = (float)($data2[10] - $data2[9]);
   
    if ($avr1 == 0) $avr1 = $basevr;
    if ($avr2 == 0) $avr2 = $basevr;
    if ($avr3 == 0) $avr3 = $basevr;
    if ($avr4 == 0) $avr4 = $basevr;
    if ($avr5 == 0) $avr5 = $basevr;
    if ($avr6 == 0) $avr6 = $basevr;
    if ($avr7 == 0) $avr7 = $basevr;
    if ($avr8 == 0) $avr8 = $basevr;
    if ($avr9 == 0) $avr9 = $basevr;
    if ($avr10 == 0) $avr10 = $basevr;
    if ($avr11 == 0) $avr11 = $basevr;
    if ($avr12 == 0) $avr12 = $basevr;
    
 /*   $avr1 = $basevr;
      $avr2 = $basevr;
      $avr3 = $basevr;
   $avr4 = $basevr;
     $avr5 = $basevr;
     $avr6 = $basevr;
    $avr7 = $basevr;
   $avr8 = $basevr;
    $avr9 = $basevr;
   $avr10 = $basevr;
     $avr11 = $basevr;
     $avr12 = $basevr; */
    
    $cm =  date('m', strtotime('month'));
    if ($jan3 == 0){
        $data['avr1'] =  (float)number_format($dec2,2);
    } else $data['avr1'] = $jan3;
    $xx = $data['avr1']; 
    
    $data['avr2'] = 0;
    if ((int)$feb3 == 0){
        $data['avr2'] = (float)number_format((($avr1  + $xx) ),2);
    } else $data['avr2]'] = $feb3;
  //  var_dump($xx, $avr2, $data['avr2']);
    $xx = $data['avr2']; 
    
    
    $data['avr3'] = (float)number_format((($avr2  + $xx) ),2);
    $xx = $data['avr3']; 
    $data['avr4'] = (float)number_format((($avr3  + $xx) ),2);
    $xx = $data['avr4']; 
    
    $data['avr5'] = (float)number_format((($avr4  + $xx) ),2);
    $xx = $data['avr5']; 
    $data['avr6'] = (float)number_format((($avr5  + $xx) ),2);
    $xx = $data['avr6']; 
    $data['avr7'] = (float)number_format((($avr6  + $xx) ),2);
    $xx = $data['avr7']; 
    
    $data['avr8'] = (float)number_format((($avr7  + $xx) ),2);
 //   var_dump($xx, $avr8, $data['avr8']);
    $xx = $data['avr8']; 
    $data['avr9'] = (float)number_format((($avr8  + $xx) ),2);
    $xx = $data['avr9']; 
    $data['avr10'] = (float)number_format((($avr9  + $xx) ),2);
    $xx = $data['avr10']; 
    
    $data['avr11'] = (float)number_format((($avr10  + $xx) ),2);
    $xx = $data['avr11']; 
    $data['avr12'] = (float)number_format((($avr11  + $xx) ),2);
    $xx = $data['avr12']; 
    
    
    $datax['avr1'] =  (float)number_format((($avr12  + $xx) ),2);
    $xx = ($avr1  * $dec1) + $dec1;
    $datax['avr2'] = (float)number_format((($avr1  + $xx) ),2);
    $xx = ($avr2  + $xx);
    $datax['avr3'] = (float)number_format((($avr2  + $xx) ),2);
    $$xx = ($avr3  + $xx);
    $datax['avr4'] = (float)number_format((($avr3  + $xx) ),2);
    $xx = ($avr4  + $xx);
    
    $datax['avr5'] = (float)number_format((($avr4  + $xx) ),2);
    $xx = ($avr5  + $xx);
    $datax['avr6'] = (float)number_format((($avr5  + $xx) ),2);
    $xx = ($avr6  + $xx);;
    $datax['avr7'] = (float)number_format((($avr6  + $xx) ),2);;
    $xx = ($avr7  + $xx);
    $datax['avr8'] = (float)number_format((($avr7  + $xx) ),2);
    $xx = ($avr8  + $xx);
    $datax['avr9'] = (float)number_format((($avr8  + $xx) ),2);
    $xx = ($avr9  + $xx);
    $datax['avr10'] = (float)number_format((($avr9  + $xx) ),2);
    $xx = ($avr10  + $xx);
    
    $datax['avr11'] = (float)number_format((($avr10  + $xx) ),2);
    $xx = ($avr11  + $xx);
    $datax['avr12'] = (float)number_format((($avr11  + $xx) ),2);
    $xx = ($avr12  + $xx);
    
    
     
            
      $datay['nextyear'] = $datax;  
      $datay['cyear'] = $data;  
      $datay['avr'] = array($avr1, $avr2, $avr3, $avr4, $avr5, $avr6, $avr7, $avr8, $avr9, $avr10, $avr11, $avr12);  
    
    
    
    
    return $datay;
    
    
}