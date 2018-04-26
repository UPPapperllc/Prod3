<?php
// Return the menu options for this user;

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


// Generic all users functions;

$menu = UPClass2::checkMenuOptions($con);

?>

<html>
<head>
<title>Production Review and Reporting Menu</title>
 <link rel="stylesheet" href="prod3.css">
 
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


</style>
 
 <script>

 var interval;
 window.addEventListener("move", function(evt){ 
	 console.log('Event: ', evt);
   if (evt.toElement === null && evt.relatedTarget === null) {
     //if outside the window...
     if (console) console.log("out");
     interval = setInterval(function () {
       //do something with evt.screenX/evt.screenY
     }, 250);
   } else {
     //if inside the window...
     if (console) console.log("in");
     clearInterval(interval);
   }
 });
 function closeWin(){
	 window.close();
 }

 </script>
</head>
<body>
<h5><title>Production Review and Reporting Menu</title></h5>

  
    <?php  echo $menu;?>
 
 <button onclick="closeWin()"> Sign-off</button>

</body>
</html>

<!-- 

<upmenu class="floating-menu">
 <div class="dropdown">
  <button class="dropbtn">UP Functions</button>
  <div class="dropdown-content">
   </div>
</div>
 </upmenu>
 -->