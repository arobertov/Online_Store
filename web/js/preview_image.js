$(document).ready(function () {
    let imageId = [];
    /* ------ Select images from modal list ------------- */
    $('.card').click(function () {
        let imageCard = $(this).parent().clone();
        let dataId = $(this).data('id');
        let imagesFormField = $('#product-form-img').find(`div[data-id=${dataId}]`);
        /* ------- Create close button and attach event ----------------- */
        let closeButton = $(`<img src="/images/admin_panel/close-browser.png" data-id="${dataId}" class="close-image-icon" alt="close-image"/>`);
        closeButton.click(function () {
            $(this).parent().parent().fadeOut('3000').remove();
        });

        $(this).find('.check-icon').toggle('1000');
        $(this).toggleClass('check-shadow');
        /* ------- Load images into create product form ------------ */
        if(imagesFormField.length === 0){
            imageCard.children().append(closeButton);
            imageCard.appendTo('#product-form-img');
        } else {
            imagesFormField.parent().fadeOut().remove();
        }
    });

    $('.close-image-icon').click(function () {
        detachImage($(this));
    });

    function detachImage(closeBtn){
        let dataId = closeBtn.data('id');
        $.ajax({
            method: 'POST',
            data:'id='+dataId,
            url: '../detach_images/'+closeBtn.data('product'),
            success: function (e) {
                console.log(e.toString());
                $(this).parent().parent().fadeOut('3000').remove();
            },
            error:function(msg){
               console.log(msg)
            }
        })
    }

    /* ------- Close modal images list ------------- */
    $('#add-images-btn').click(function () {
        $('.check-icon').hide();
        $('.card').removeClass('check-shadow');
        $('#add-images-modal').modal('hide');
    });

    $('#submit-product-form').click(function (e) {
        let myForm = $('form[name="shopbundle_product"]');
        addImagesIdsInput(myForm);
    });

    function addImagesIdsInput(myForm) {
        let getFormImages = $('#product-form-img').find('.card');
        getFormImages.each(function () {
            imageId.push($(this).data('id'));
        });
        let imagesIds = imageId.toString();
        $(`<input type="hidden" name="image_ids" value="${imagesIds}"/>`).appendTo(myForm);
    }
});