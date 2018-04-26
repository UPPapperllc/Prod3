
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>jQuery UI Sortable - Portlets</title>
	<link rel="stylesheet" href="../jqb/jquery-ui.css">
<!-- 	<link rel="stylesheet" href="/resources/demos/style.css"> -->
	<style>
	body {
		min-width: 520px;
	}
	.column {
		width: 300px;
		float: left;
		padding-bottom: 100px;
		border: 1px solid black;
	}

	.portlet {
		margin: 0 1em 1em 0;
		padding: 0.3em;
	}
	.portlet-header {
		padding: 0.2em 0.3em;
		margin-bottom: 0.5em;
		position: relative;
	}
	.portlet-toggle {
		position: absolute;
		top: 50%;
		right: 0;
		margin-top: -8px;
	}
	.portlet-content {
		padding: 0.4em;
	}
	.portlet-placeholder {
		border: 1px dotted black;
		margin: 0 1em 1em 0;
		height: 50px;
	}
	</style>
	<script src="../jqb/external/jquery/jquery.js"></script>
	<script src="../jqb/jquery-ui.js"></script>
	<script>
	$( function() {
		$( ".column" ).sortable({
			connectWith: ".column",
			handle: ".portlet-header",
			cancel: ".portlet-toggle",
			placeholder: 'ui-state-highlight',
			//revert: true,
		    receive: function(event, ui) {
		     console.log(ui);
		     
		     console.log(ui.item.data("order"));
		     console.log(ui.item.data("shipment"));
		     console.log(event);
		     console.log(event.target.id);
		    
		     
		    }
		});

		$( ".portlet" )
			.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
			.find( ".portlet-header" )
				.addClass( "ui-widget-header ui-corner-all" )
				.prepend( "<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");

		$( ".portlet-toggle" ).on( "click", function() {
			var icon = $( this );
			icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
			icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
		});
		 
	} );
	</script>

  
	
	
</head>
<body>

<div class="column" id='Open'>
<div><h3>Open orders</h3></div>
	<div class="portlet" data-id= "1" data-order= "K12345" data-shipment= "1">
		<div class="portlet-header">Order K12345 - Shipment 1</div>
		<div class="portlet-content"><table>
		<tr><th>Grade</th><td>xx-xx-xxxxx</td></tr>
		<tr><th>Required Date</th><td>xx-xx-xx</td></tr>
		</table></div>
	</div>

	<div  class="portlet" data-id= "2" data-order= "K12345" data-shipment= "2">
		<div class="portlet-header">Order K12345 - Shipment 2</div>
		<div class="portlet-content"><table>
		<tr><th>Grade</th><td>xx-xx-xxxxx</td></tr>
		<tr><th>Required Date</th><td>xx-xx-xx</td></tr>
		</table></div>
	</div>

</div>
<div class="column" id='Production' >
<div><h3>Assigned to Prod Order</h3></div>
<div  class="portlet" data-id= "3">
		<div class="portlet-header">Order K12345 - Shipment 3</div>
		<div class="portlet-content"><table>
		<tr><th>Grade</th><td>xx-xx-xxxxx</td></tr>
		<tr><th>Required Date</th><td>xx-xx-xx</td></tr>
		</table></div>
	</div>

	</div>

<div class="column" id='Produced' >
<div><h3>Produced</h3></div>

	<div  class="portlet" data-id= "4" data-order= "K12345" data-shipment= "4">
		<div class="portlet-header">Order K12345 - Shipment 4</div>
		<div class="portlet-content"><table>
		<tr><th>Grade</th><td>xx-xx-xxxxx</td></tr>
		<tr><th>Required Date</th><td>xx-xx-xx</td></tr>
		</table></div>
	</div>

	<div  class="portlet" data-id= "5" data-order= "K12345" data-shipment= "5">
		<div class="portlet-header">Order K12345 - Shipment 5</div>
		<div class="portlet-content"><table>
		<tr><th>Grade</th><td>xx-xx-xxxxx</td></tr>
		<tr><th>Required Date</th><td>xx-xx-xx</td></tr>
		</table></div>
	</div>

</div>






</body>
</html>
