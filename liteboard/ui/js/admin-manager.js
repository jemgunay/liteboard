$(document).ready(function() {
    // login form submit
    $("#btn-change-pass").click(function(e) {
        var select_value = $('#account-input').find('option:selected').attr('value');

        $.ajax({
            type: "POST",
            url: rootPath + "admin/change_password/" + select_value,
            data: $('#form-change-password').serialize(),
            dataType: "text",
            success: function (e) {
                if (e == "success") {
                    // log in successful
                    $('#error-container-pass').empty().append(e);
                } else {
                    // display log in error
                    $('#error-container-pass').empty().append(e);
                    $('#old-pass-input').val('');
                    $('#new-pass-input').val('');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                return;
                if (XMLHttpRequest.readyState == 4) {
                    // HTTP error
                    $('#error-container-pass').empty().append("<strong>Connection error: Request is invalid.</strong>");
                }
                else if (XMLHttpRequest.readyState == 0) {
                    // Network error
                    $('#error-container-pass').empty().append("<strong>Network error: Please check your internet connection.</strong>");
                }
                else {
                    $('#error-container-pass').empty().append("<strong>An unexpected error occurred.</strong>");
                }
            }
        });
    });
});
