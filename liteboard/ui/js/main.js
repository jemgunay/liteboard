var rootPath = "../../";

$(document).ready(function() {

    // full calendar initialise
    $("#calendar").fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'listYear2,month2,listWeek2,listDay2'
        },
        views: {
            listYear2: {
                type: 'list',
                duration: { years: 2 },
                buttonText: 'All'
            },
            month2: {
                type: 'month',
                buttonText: 'Month'
            },
            listWeek2: {
                type: 'list',
                duration: { weeks: 1 },
                buttonText: 'Week'
            },
            listDay2: {
                type: 'list',
                duration: { days: 1 },
                buttonText: 'Day'
            }
        },
        editable: false,
        timezone: 'Europe/London',
        windowResizeDelay: 10,
        height: 550,
        defaultView: 'listYear2',
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

// temporarily disable button click to prevent accidental double clicks, enable after timer expires
function checkButtonReady(buttonId) {
    if ($(buttonId).attr('disabled') == true)
        return false;

    $(buttonId).attr('disabled', true);
    setTimeout(function() {
        $(buttonId).attr('disabled', false);
    }, 1000);
    return true;
}