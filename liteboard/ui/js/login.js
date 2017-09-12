$(document).ready(function() {
    // login form submit
    $("#btn-login").click(function(e) {
        e.preventDefault();
        performLogin();
    });

    // login form submit on enter press
    $('#login-password').keypress(function (e) {
        if (e.which == 13) {
            performLogin();
            return false;
        }
    });

    // perform ajax post login request
    function performLogin() {
        $.ajax({
            type: "POST",
            url: rootPath + "login",
            data: $('.form-login').serialize(),
            dataType: "text",
            success: function (e) {
                if (e == "success") {
                    // log in successful
                    window.location.href = rootPath + "news";
                } else {
                    // display log in error
                    $('#login-password').val('');
                    $('#alert-container').empty().append(e);
                    $('#login-password').focus();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (XMLHttpRequest.readyState == 4) {
                    // HTTP error
                    $('#alert-container').empty().append("<strong>Network error: Please check your internet connection.</strong>");
                }
                else if (XMLHttpRequest.readyState == 0) {
                    // Network error
                    $('#alert-container').empty().append("<strong>Network error: Please check your internet connection.</strong>");
                }
                else {
                    $('#alert-container').empty().append("<strong>An unexpected error occurred.</strong>");
                }
            }
        });
    }
});
