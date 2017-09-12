$(document).ready(function() {
    // create & edit calendar event
    $('#calendar-modal').on('show.bs.modal', function(e) {
        // reset fields
        $('#form-calendar')[0].reset();
        $('#calendar-delete').hide();
        $('#error-container').empty();
        $('#calendar-modal-label').text("Create Calendar Event");
        $('#calendar-submit').text("Create");
        $(this).find('.colorpicker-component').colorpicker('setValue', $(this).find('.colorpicker-component').data('default'));
        // focus on input
        $(this).on('shown.bs.modal', function(e) { $('#title-input').focus(); });
        // get target info
        var target = $(e.relatedTarget).data('action');

        // edit calendar
        if (target != "create") {
            target = "edit";
            $('#calendar-modal-label').text("Edit Calendar Event");
            $('#calendar-submit').text("Submit");
            $('#delete-submit').attr('data-target-id', calEvent.id);
            $('#calendar-delete').show();

            $('#title-input').val(calEvent.title);
            $('#date-start-input').val(moment(calEvent.start).format('YYYY-MM-DD'));
            $('#date-end-input').val(moment(calEvent.end).format('YYYY-MM-DD'));
            $('#time-start-input').val(moment(calEvent.start).format('HH:mm'));
            $('#time-end-input').val(moment(calEvent.end).format('HH:mm'));
            // set all day
            if (moment(calEvent.start).format('HH:mm') == "00:00") {
                $('#time-start-input').val("");
            }
            if (calEvent.allDay) {
                $('#date-end-input').val($('#date-start-input').val());
            }
            $(this).find('.colorpicker-component').colorpicker('setValue', calEvent.color);
        }

        // submit changes
        $('#calendar-submit').unbind();
        $('#calendar-submit').click(function(e) {
            var data = {};
            if (target == "edit") {
                data['event_id'] = calEvent.id;
            }
            // default end date if end time is specified
            if ($('#time-end-input').val() != "" && $('#date-end-input').val() == "") {
                $('#date-end-input').val($('#date-start-input').val());
            }

            data['title'] = $('#title-input').val();
            data['date_start'] = $('#date-start-input').val();
            data['date_end'] = $('#date-end-input').val();
            // join date & time values if time exists
            if ($('#time-start-input').val() != "") {
                data['date_start'] = data['date_start'] + "T" + $('#time-start-input').val();

                if ($('#time-end-input').val() != "") {
                    data['date_end'] = data['date_end'] + "T" + $('#time-end-input').val();
                }
            }
            data['colour'] = $('.colorpicker-component').colorpicker('getValue');

            perform_ajax('calendar/' + target, data)
        });
    });

    /*********** delete multiple types ***********/
    $('#delete-modal').on('show.bs.modal', function(e) {
        data = {};
        data["event_id"] = $('#delete-submit').attr('data-target-id');

        $('#delete-modal-label').text("Delete event?");
        $('#delete-modal .modal-body p').text("Are you sure you want to delete this event?");

        $("#delete-submit").unbind();
        $("#delete-submit").click(function() {
            perform_ajax("calendar/delete", data);
        });
    });


    /*********** perform ajax POST request & redirect to news ***********/
    function perform_ajax(target, formData) {
        $.ajax({
            type: "POST",
            url: rootPath + target,
            data: formData,
            success: function(e) {
                if (e == "success") {
                    window.location.href = rootPath + "calendar";
                } else {
                    $('.error-container').empty().append(e);
                }
            },
            error: function(e) {
                console.log(e);
            },
            async: false
        });
    }
});

// calendar click event
var calendarAdmin = true;
var calEvent;
function edit_event(event) {
    calEvent = event;
    $('#calendar-modal').modal('toggle');
}