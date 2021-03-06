
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Calendar Sample: Desktop</title>				
		<style type="text/css">
			@import "../dojo/dojox/calendar/themes/claro/Calendar.css";
			@import "../dojo/dojox/calendar/themes/claro/MonthColumnView.css";
			@import "demo.css";
			@import "../dojo/dojo/resources/dojo.css";
			@import "../dojo/dijit/themes/dijit.css";
			@import "../dojo/dijit/themes/claro/claro.css";							
		</style>			
	</head>
	
	<body class="claro">
		<script type="text/javascript" data-dojo-config="async: true, parseOnLoad: true" src="../../dojo/dojo.js"></script>
		<script type="text/javascript" src="src.js"></script>

		<div id="loadingPanel" style="position:absolute;z-index:10;width:100%;height:100%;background:#ffffff">
			<span style="background: #DBEB8F;padding:2px">Loading...</span>
		</div>
		
		<div data-dojo-type="dijit/layout/BorderContainer" style="width:100%;height:100%" data-dojo-props="design:'sidebar', gutters:true" >
			<div data-dojo-type="dijit/layout/AccordionContainer" data-dojo-props="splitter:false, region:'leading'">
				<div data-dojo-type="dijit/layout/ContentPane" title="Main properties" style="width:250px">
					<div data-dojo-id="mainProperties" data-dojo-type="demos/calendar/MainProperties" style="width:230px" ></div>
				</div>
				<div data-dojo-type="dijit/layout/ContentPane" title="Column view properties">
					<div data-dojo-id="columnViewProperties" data-dojo-type="demos/calendar/ColumnViewProperties" style="width:250px" ></div>
				</div>
				<div data-dojo-type="dijit/layout/ContentPane" title="Matrix view properties">
					<div data-dojo-id="matrixViewProperties" data-dojo-type="demos/calendar/MatrixViewProperties" style="width:250px" ></div>
				</div>
				<div data-dojo-type="dijit/layout/ContentPane" title="Month column view properties">
					<div data-dojo-id="monthColumnViewProperties" data-dojo-type="demos/calendar/MonthColumnViewProperties" style="width:250px" ></div>
				</div>
			</div>							    		        			  
			<div data-dojo-type="dijit/layout/ContentPane" data-dojo-props="splitter:false, region:'center'">
				<div data-dojo-id="calendar" data-dojo-type="demos/calendar/ExtendedCalendar" 
					style="position:absolute;left:10px;top:10px;bottom:30px;right:10px"></div>	
				<div id="hint" style="position:absolute;left:10px;height:15px;bottom:10px;right:10px;color:#999;overflow:auto"></div>
			</div>		   
		</div>																		

	</body>
</html>

