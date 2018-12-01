$(document).ready(function () {
    //* ------ hide success message ------------- *//
    $('.alert-success').fadeOut(10000);

//* ------ create Ajax request for  modal login form ------------ *//
    var frm = $('#formLoginModal');
    var errMsgField = $('#err_message');

    errMsgField.hide();
    frm.submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (v) {
                // ---- if success,  redirect to homepage ------- //
                $(location).attr('href','/');
            },
            error: function (errorMsg) {
                // --- if error, send error message from login form error field ----- //
                var errText = JSON.parse( errorMsg.responseText );
                errMsgField.show();
                errMsgField.empty();
                errMsgField.append(errText['error']!==undefined?errText['error']:errorMsg);
            }
        });
    });
    // ----- feather icons ------- //
    feather.replace();

    function setActiveTab(tabId) {
        $('#' + tabId).tab('show')
    }
});

