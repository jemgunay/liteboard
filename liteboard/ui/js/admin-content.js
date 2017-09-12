$(document).ready(function() {
    var parent_folder = $('#current-folder').attr('data-folder-id');

    // arrangement changing arrows
    $('.btn-arrange:not(:disabled)').click(function() {
        var data = { 'content_id': $(this).attr('data-content-id') };
        data['direction'] = $(this).attr('data-direction');
        perform_ajax('content/' + parent_folder + '/rearrange', data);
    });

    // quill create
    $('#btn-create-description').click(function() {
        var data = { 'target_type': 'editor' };
        perform_ajax('content/' + parent_folder + '/create', data);
    });

    // quill editor
    $('.btn-quill-edit:not(:disabled)').click(function() {
        var target_id = $(this).attr('data-target-id');

        // disable other UI buttons, storing state of arrangement arrows for reset after save
        var media_admin = $('.media-admin[data-target-id='+target_id+']');
        var arrow_states = [media_admin.find('button[data-direction=up]').prop('disabled'), media_admin.find('button[data-direction=down]').prop('disabled')];
        media_admin.find('button:not(.btn-delete)').prop('disabled', true);
        // enable editor save & cancel buttons
        $('.media-editor .form-inline[data-target-id='+target_id+']').css('display', 'block');

        // init quill corresponding to button
        var target_quill = $('.quill-editor[data-target-id='+target_id+']');
        var target_quill_copy = target_quill.clone();
        target_quill.css('min-height', 175);

        var quill = new Quill(target_quill[0], {
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'code-block'],
                    ['clean']
                ]
            },
            theme: 'snow',
            bounds: '.media-editor'
        });

        // submit
        $(this).closest('.media').find('#btn-quill-save').click(function() {
            var data = { 'target_id': target_id };
            data['target_type'] = 'editor';
            data['text'] = quill.root.innerHTML;
            perform_ajax('content/' + parent_folder + '/edit', data);
            clean_up_editor();
        });
        // cancel
        $(this).closest('.media').find('#btn-quill-cancel').click(function() {
            clean_up_editor();
        });

        function clean_up_editor() {
            // replace initialised quill with new one
            target_quill.closest('.quill-container').empty().append(target_quill_copy);
            // disable editor save & cancel buttons
            $('.media-editor .form-inline[data-target-id='+target_id+']').css('display', 'none');
            // reset button states
            media_admin.find('button').prop('disabled', false);
            if (arrow_states[0])
                media_admin.find('button[data-direction=up]').prop('disabled', true);
            if (arrow_states[1])
                media_admin.find('button[data-direction=down]').prop('disabled', true);

        }
    });

    // create & edit folders & files
    $('#template-modal').on('show.bs.modal', function(e) {
        var action = $(e.relatedTarget).data('action');
        var type = $(e.relatedTarget).data('type');
        var target_id = $(e.relatedTarget).data('target-id');
        var fields = $(e.relatedTarget).data('fields');
        $('#template-modal-title').text(uc_first(action) + " " + uc_first(type));
        $('#template-submit').text(uc_first(action));
        $('.error-container').empty();
        $('.modal-form').hide();

        if (type == 'file') {
            $('.fileinput').fileinput('clear');
            $('#description-input').val('');
            $('#modal-file').show();
            $('span.fileinput-new').text('No file chosen');

            setTimeout(function() {
                $('#description-input').focus();
            }, 500);

            if (action == 'edit') {
                $('span.fileinput-new').text('Choose a file to replace the current one');
                $('#description-input').val(fields[1]);
            }
        }
        else if (type == 'folder') {
            $('#modal-folder form').trigger("reset");
            $('#modal-folder').show();

            setTimeout(function() {
                $('#name-input').focus();
            }, 500);

            if (action == 'edit') {
                $('#name-input').val(fields[0]);
                $('#folder-description-input').val(fields[1]);
            }
        }

        // submit
        $('#template-submit').unbind();
        $('#template-submit').click(function(){
            var data = {};

            if (type == 'file') {
                data = new FormData($('#modal-file form')[0]);
                data.append('target_type', type);
                if (action == 'edit')
                    data.append('target_id', target_id);

                perform_ajax('content/' + parent_folder + '/' + action, data, false);
            }
            else if (type = 'folder') {
                data = $('#modal-folder form').serializeArray();
                data.push({name: 'target_type', value: type});
                if (action == 'edit')
                    data.push({name: 'target_id', value: target_id});
                data = $.param(data);

                perform_ajax('content/' + parent_folder + '/' + action, data);
            }
        });
    });

    // set folder name as date btn
    $('#btn-folder-date').click(function(){
        $('#name-input').val(moment().format('DD/MM/YYYY'));
    });

    /** delete modal management **/
    $('#delete-modal').on('show.bs.modal', function(e) {
        var action = $(e.relatedTarget).data('action');
        var data = { 'target_type': action };
        data['content_id'] = $(e.relatedTarget).data('content-id');

        // title formatting
        if (action == 'editor')
            action = 'description';
        $('#delete-modal-label').text("Delete " + uc_first(action) + "?");
        // body formatting
        var delete_message = "Are you sure you want to delete this " + action + "?";
        if (action == 'folder')
            delete_message += "<br><br><strong>Any subfolders and files within this folder will also be permanently deleted.<strong>";
        // submit formatting
        $('#delete-modal .modal-body p').html(delete_message);

        // delete btn submit
        $("#delete-submit").unbind();
        $("#delete-submit").click(function() {
            perform_ajax('content/' + parent_folder + '/delete', data);
        });
    });


    /** perform AJAX POST request & refresh **/
    function perform_ajax(target, formData, override_setting) {
        ajax_processData = typeof override_setting !== 'undefined' ? override_setting : true;
        ajax_contentType = typeof override_setting !== 'undefined' ? override_setting : 'application/x-www-form-urlencoded; charset=UTF-8';

        $.ajax({
            type: "POST",
            url: rootPath + target,
            data: formData,
            processData: ajax_processData,
            contentType: ajax_contentType,
            success: function(e) {
                if (e == "success") {
                    window.location.reload();
                }
                else {
                    $('.error-container').empty().append(e);
                    console.log(e);
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    }

    // upper case first letter
    function uc_first(str) {
        return str.charAt(0).toUpperCase() + str.slice(1)
    }
});