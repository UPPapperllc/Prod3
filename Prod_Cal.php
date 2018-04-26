<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='../FullCal/lib/fullcalendar.min.css' rel='stylesheet' />
<link href='../FullCal/lib/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<link href='../FullCal/scheduler.min.css' rel='stylesheet' />
<script src='../FullCal/lib/moment.min.js'></script>
<script src='../FullCal/lib/jquery.min.js'></script>
<script src='../FullCal/lib/fullcalendar.min.js'></script>
<script src='../FullCal/scheduler.min.js'></script>
<script>

	$(function() { // document ready

		$.getJSON("getProdCal.php?action=list", function (result) {
	          events = result.events;
	          resources = result.resources;

		
		$('#calendar').fullCalendar({
		//	now: '2017-02-07',
			editable: true,
			aspectRatio: 1.8,
			scrollTime: '00:00',
			header: {
				left: 'today prev,next,timelineDay,timelineThreeDays,agendaWeek',
				center: 'title',
				right: 'timelineDay,timelineThreeDays,agendaWeek,month'
			},
			defaultView: 'agendaWeek',
			views: {
				timelineThreeDays: {
					type: 'timeline',
					duration: { days: 3 }
				}
			},
			eventResize: eventResize,
            //***********************************************************************************
			eventDrop: function(event, Delta, revertFunc) {
			console.log(Delta._milliseconds, Delta._days);
			       // alert(event.title + " was dropped on " + event.start.format());
			 chgHrs = (((Delta._milliseconds/1000)/60)/60) + (Delta._days * 24);
			        if (!confirm("Adjust Schedule " + chgHrs + ' Hours?')) {
			            revertFunc();
			        } else {
			    

			           

			            url = "/Prod3/ChangeProdSched.php?DB=PR&ID=" + event.id + "&GRD8=" + event.GRD8 + '&chgHrs=' + chgHrs + '&sdate=' + event.sdate + '&Action=DD';  
			            console.log(url);
			            window.open(url, "Uppdate", "width=600,height=700");
			        }

			    },

			    //***********************************************************************************
						
			
			resourceGroupField: 'grade',
			resources: resources,
			events: events
		});
		});



		 //***********************************************************************************
        function eventResize(event,Delta,revertFunc) {

     
          
          console.log('Update', Delta._milliseconds);
          chgHrs = (((Delta._milliseconds/1000)/60)/60)  + (Delta._days * 24);    
              if (!confirm("Adjust Schedule Hours " + chgHrs + ' Hours?'))  {
          revertFunc();
      } else {
  
          

          url = "/Prod3/ChangeProdSched.php?DB=PR&ID=" + event.id + "&GRD8=" + event.GRD8 + '&chgHrs=' + chgHrs + '&eHours=' + event.ESTHOURS +  '&Action=CT';  
          console.log(url);
          window.open(url, "Uppdate", "width=600,height=300");
   //       console.log('Change view to :', getCookie('lastView'));
  //   $('#calendar').fullCalendar( 'changeView',getCookie('lastView'))
      }
              
              
      }
  //***********************************************************************************


		
		
	//	$('#calendar').fullCalendar({
	//		  schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source'
	//		});
	});

</script>
<style>

	body {
		margin: 0;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 12px;
	}

	#calendar {
		max-width: 1200px;
		margin: 50px auto;
		width: 90%;
		height: 90%;
	}

</style>
</head>
<body>

	<div id='calendar'></div>

</body>
</html>

