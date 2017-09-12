var rootPath = "../../";

$(document).ready(function() {

    // full calendar initialise
    $("#calendar").fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'listMonth,month,agendaWeek,agendaDay'
        },
        editable: false,
        timezone: 'Europe/London',
        windowResizeDelay: 10,
        height: 550,
        defaultView: 'listMonth',
        events: {
            url: (rootPath + 'calendar/fetch'),
            type: 'GET',
            dataType: 'json',
            error: function(e) {
                console.log('There was an error fetching calendar events:');
                console.log(e);
            }
        },
        eventClick: function(event) {
            // calendar event click
            if (typeof calendarAdmin !== 'undefined') {
                edit_event(event);
            }
            return false;
        }
    });

    // color picker initialise
    $('.colorpicker-component').colorpicker();
});
