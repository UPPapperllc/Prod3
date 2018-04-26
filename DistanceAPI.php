<?php 
header('Access-Control-Allow-Origin: *');
include '../uphead.php';
$cust = $_GET['CUST'];
$s = "Select * from HDCUST where CMCUST = $cust";
$r = db2_exec($con, $s);
$row = db2_fetch_assoc($r);
$zip = $row['CMZIP'];
$cs = trim($row['CMCCTY']) . '+' . trim($row['CMST']);
$cs = str_replace(' ', '+', $cs);
$winc = 'LTT' . $cust;
?>
<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  
    });


$( window ).on( "load", function() {
	  var url = 'https://cors.io/?https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=49854&destinations=<?php echo $cs;?>&key=AIzaSyAqIVNLi2wrYIjx71kFYw_uN5LPiVqtyUA';
      var c=0;
      $.ajax({
          async: false,
          type: "GET",
          url: url,
          dataType: "json",
          success: function (res) {
              
        	  console.log('result: ', res);
              console.log('Time: ', res.rows[0].elements[0].duration.value);
              console.log('Distance: ', res.rows[0].elements[0].distance.value);
             var v = SecondsTohhmmss(res.rows[0].elements[0].duration.value);
             var m = Math.round(res.rows[0].elements[0].distance.value / 1608.61);
            console.log(v, m);
          
         
           chgurl  = "setTruckTransitTime.php?CUST=<?php echo $cust;?>&TH=" + v[0] + "&TM=" + v[1] + "&TS=" + v[2] + "&MILES=" + m + "&ZIP=<?php echo $zip;?>";   
            window.open(src=chgurl,'<?php echo $winc; ?>', "width=400,height=100");

            window.close();
    }
          });
      });


      
 //     $.getJSON(url, function(result){
 //     	console.log('result: ', result);
 //         console.log('Time: ', result.rows[0].elements[0].duration.value);
 //         console.log('Distance: ', result.rows[0].elements[0].distance.value);
  //       var v = SecondsTohhmmss(result.rows[0].elements[0].duration.value);
  //       var m = Math.round(result.rows[0].elements[0].distance.value / 1608.61);
  //      console.log(v, m);
  //      winc = 'ST' + c;
  //     chgurl  = "setTruckTransitTime.php?CUST=<?php echo $cust;?>&TH=" + v[0] + "&TM=" + v[1] + "&TS=" + v[2] + "&MILES=" + m + "&ZIP=<?php echo $zip;?>";   
  //      window.open(src=chgurl, winc, "width=400,height=100");
//
//        window.close();
//});
//});

function SecondsTohhmmss(totalSeconds) {
	  var hours   = Math.floor(totalSeconds / 3600);
	  var minutes = Math.floor((totalSeconds - (hours * 3600)) / 60);
	  var seconds = totalSeconds - (hours * 3600) - (minutes * 60);

	  // round seconds
	  seconds = Math.round(seconds * 100) / 100
var result = [hours, minutes, seconds];
	  return result;
	}

    </script>
    </head>
    <body>
    
  
    
    <div></div>
    
    </body>
    </html>

