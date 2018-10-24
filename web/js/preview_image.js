$(document).ready(function () {
    let imageId = [];
    $('.card').click(function () {
        $(this).find('.check-icon').toggle('1000');
        $(this).toggleClass('check-shadow');
        imageId.push($(this).data('id'));
    });

    $('#add-images-btn').click(function () {
        $.ajax({
            type: "POST",
            url: '',
            data: imageId,
            success: function (d) {
                $('#add-images-modal').modal('hide');

            }
        });
    })
});