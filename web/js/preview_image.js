$(document).ready(function () {
    let imageId = [];
    /* ------ Select images from modal list ------------- */
    $('.card').click(function () {
        /* ------- Create close button and attach event ----------------- */
        let closeButton = $('<img src="/images/admin_panel/close-browser.png" class="close-image-icon" alt="close-image"/>');
        closeButton.click(function () {
            $(this).parent().parent().fadeOut('3000').remove();
        });
        let imageCard = $(this).parent().clone();
        let dataId = $(this).data('id');
        let imagesFormField = $('#product-form-img').find(`div[data-id=${dataId}]`);

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

    /* ------- Close modal images list ------------- */
    $('#add-images-btn').click(function () {
        $('.check-icon').hide();
        $('.card').removeClass('check-shadow');
        $('#add-images-modal').modal('hide');
    });

    $('#submit-product-form').click(function (e) {
        let productForm = $('form[name="shopbundle_product"]');
        addImagesIdsInput(productForm);
    });

    function addImagesIdsInput(productForm) {
        let getFormImages = $('#product-form-img').find('.card');
        getFormImages.each(function () {
            imageId.push($(this).data('id'));
        });
        let imagesIds = imageId.toString();
        $(`<input type="hidden" name="image_ids" value="${imagesIds}"/>`).appendTo(productForm);
    }
});