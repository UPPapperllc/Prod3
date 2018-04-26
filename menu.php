<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require '../uphead.php';


?>
<html>
<head>
<meta http-equiv="refresh" content="3;url=http://www.google.com/" />
<title>Production Review and Reporting Menu</title>
 <link rel="stylesheet" href="prod3.css">


</head>

	<body>
<script>
var windowName = 'userMenu'; 


var childWindowName = "userMenu"
var handle = window.open("",childWindowName);
if (handle && handle.open && !handle.closed){
	handle.close();
}



var viewportwidth = window.innerWidth;
var viewportheight = document.documentElement.clientHeight;
window.resizeBy(-200,0);
//window.moveTo(0,0);


console.log('Width: ', viewportwidth );
url = "UPMenu.php";

var popUp = window.open(url,windowName, "width=500, height=400, left="+(viewportwidth-300)+",top=0");
if (popUp == null || typeof(popUp)=='undefined') { 	
	alert('Please disable your pop-up blocker and click the "Open" link again.'); 
} 
else { 	
	popUp.focus();
}


window.close();



				var childWindowName = "userMenu_"+i;
				var handle = window.open("",childWindowName);
				if (handle && handle.open && !handle.closed){
					handle.close();
				}
		

</script>


</body>

</html>