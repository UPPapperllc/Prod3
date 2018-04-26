<?php

class TSClass
{
    
    
    public static function nextSheetID($con){
        $s = "Select Max(SHEETID) as SID + 1 from Toursheet";
        $r = db2_exec($con, $s);
        $row = db2_fetch_assoc($r);
        $sheetid = $row['SID'];
        
        $s = "INSERT INTO FMANSTQ/TOURSHEET_TEAM (SHEETID, SHEETDATE) VALUES($sheetid,CURRENT DATE)" ;
        $r = db2_exec($con, $s);
        $s = "INSERT INTO FMANSTQ/TOURSHEET (SHEETID) VALUES($sheetid)" ;
        $r = db2_exec($con, $s);
        $s = "INSERT INTO FMANSTQ/TOURSHEET_TIME (SHEETID,  TIMECODE,    
EVENTDATE, EVENTTIME, EVENTCOMMENT) VALUES($sheetid, 'SHIFTSTART',
CURRENT DATE, CURRENT TIME, 'New Reel')                 )" ;
        $r = db2_exec($con, $s);
        return $sheetid;
    }
    
    
    public static function newReel($con, $rollid, $prodord){
        
        $rollidTS = substr($rollid, 0,3);
        $rollnum = substr($rollid,3,2);
        $sheetid = TSClass::nextSheetID($con);
        
        $s = "Update  FMANSTQ/TOURSHEET set PRODORD = $prodord, MILRUN = $prodord, REEL = $rollnum, REELID = '$rollidTS'
Where SHEETID = $sheetid";
        $r = db2_exec($con, $s);
        $s = "Update FMANSTQ/TOURSHEET_TIME set REEL = $rollnum, REEELID = '$rollidTS' TIMECODE = 'New Reel', EVENTDATE = current date
              EVENTTIME = Current time, EVENTDUR = 0, TIMECAT = 'Process' TIMEID = 1";
        $r = db2_exec($con, $s);
    }
    
}

