$(document).ready(function () {
    $('#filterField').change(function () {
        let form = $('form');
        let postUrl = $(location).attr('pathname');
        $(this).closest(form);
        $.ajax({
            url: postUrl,
            method: 'POST',
            data:form.serialize() ,
            success:function (html) {
                $('#filterValue').replaceWith($(html).find('#filterValue'))
            },
            error:function (err) {
                console.log(err+' err_message!')
            }
        });
    });
});