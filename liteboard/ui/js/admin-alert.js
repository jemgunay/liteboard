$(document).ready(function() {

    /*********** create & edit alerts ***********/
    $('#alerts-modal').on('show.bs.modal', function(e) {
        // reset fields
        $('#form-alert')[0].reset();
        $('#error-container').empty();
        $('#alerts-modal-label').text("Create Alert");
        $('#alert-submit').text("Create");
        // focus on input
        $(this).on('shown.bs.modal', function(e) { $('#description-input').focus(); });
        // get target info
        var target = $(e.relatedTarget).data('action');

        // edit alert
        if (target == "edit") {
            $('#alerts-modal-label').text("Edit Alert");
            $('#alert-submit').text("Save");

            var inputs = $(e.relatedTarget).data('fields');
            $('#alert-id').val(inputs[0]);
            $('#description-input').val(inputs[1]);
            $('#category-input').val(inputs[2]);
        }

        // create or edit alert request on form submit
        $("#alert-submit").unbind();
        $("#alert-submit").click(function(e) {
            perform_ajax("alert/" + target, $('#form-alert').serialize());
        });

    });

    /*********** manage categories ***********/
    // reset fields to default on modal show
    $('#categories-modal').on('show.bs.modal', function(e) {
        $("tr").each(function() {
            $(this).find('input').val($(this).find('input').data('default'));
            $(this).find('.colorpicker-component').colorpicker('setValue', $(this).find('.colorpicker-component').data('default'));
            $(this).find('.label').css({"background-color" : $(this).find('.colorpicker-component').data('default')});
            $(this).find('.label').text($(this).find('input').data('default'));
        });
    });

    // input value changes
    $('.colorpicker-component').colorpicker().on('changeColor', function(e) {
        $(this).parent().closest('tr').find('.label').css({"background-color" : e.color.toString('hex')});
    });
    $('.label-text-input').on('change input', function(e) {
        $(this).parent().closest('tr').find('.label').text($(this).val());
    });

    // submit changes
    $('#categories-submit').unbind();
    $('#categories-submit').click(function(e) {
        var success = true;

        $('.existing-label').each(function(i) {
            var data = {}
            data['category_id'] = $(this).attr('id');
            data['name'] = $(this).find('.label-text-input').val();
            data['colour'] = $(this).find('.colorpicker-component').colorpicker('getValue');

            if (perform_ajax("category/edit", data, false) == false) {
                success = false;
                return;
            }
        });

        if (success)
            window.location.href = rootPath + "news";
    });

    $('#category-create-btn').click(function(e) {
        var data = {};
        data['name'] = $('#new-label .label-text-input').val();
        data['colour'] = $('#new-label .colorpicker-component').colorpicker('getValue');
        perform_ajax("category/create", data);
    });


    /*********** manage news ***********/
    $('#news-modal').on('show.bs.modal', function(e) {
        // reset fields
        $('#form-news')[0].reset();
        $('#error-container').empty();
        $('#news-modal-label').text("Create News Post");
        $('#news-submit').text("Create");
        // focus on input
        $(this).on('shown.bs.modal', function(e) { $('#news-title-input').focus(); });
        // get target info
        var target = $(e.relatedTarget).data('action');

        // edit alert
        if (target == "edit") {
            $('#news-modal-label').text("Edit News Post");
            $('#news-submit').text("Save");

            var inputs = $(e.relatedTarget).data('fields');
            $('#news-id').val(inputs[0]);
            $('#news-title-input').val(inputs[1]);
            $('#news-description-input').val(inputs[2]);
            $('#news-url-input').val(inputs[3]);
        }

        // create or edit news post request on form submit
        $("#news-submit").unbind();
        $("#news-submit").click(function(e) {
            perform_ajax("news/" + target, $('#form-news').serialize());
        });

    });


    /*********** delete multiple types ***********/
    $('#delete-modal').on('show.bs.modal', function(e) {
        var action = $(e.relatedTarget).data('action');
        var data = {}
        data[action + "_id"] = $(e.relatedTarget).data('target-id');

        $('#delete-modal-label').text("Delete " + action + "?");
        $('#delete-modal .modal-body p').text("Are you sure you want to delete this " + action + "?");

        $("#delete-submit").unbind();
        $("#delete-submit").click(function() {
            perform_ajax(action + "/delete", data);
        });
    });

    /*********** perform ajax POST request & redirect to news ***********/
    function perform_ajax(target, formData, redirect) {
        if (typeof redirect == "undefined")
            redirect = true;

        $.ajax({
            type: "POST",
            url: rootPath + target,
            data: formData,
            success: function(e) {
                if (e == "success") {
                    if (redirect) {
                        window.location.href = rootPath + "news";
                    }
                    return true;
                } else {
                    $('.error-container').empty().append(e);
                    return false;
                }
            },
            error: function(e) {
                console.log(e);
            },
            async: false
        });
    }

});
