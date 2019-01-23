$(document).ready(function () {
    $.fn.hasHandlers = function(events,selector) {
        var result=false;

        this.each(function(i){
            var elem = this;
            dEvents = $._data($(this).get(0), "events");
            if (!dEvents) {return false;}
            $.each(dEvents, function(name, handler){
                if((new RegExp('^(' + (events === '*' ? '.+' : events.replace(',','|').replace(/^on/i,'')) + ')$' ,'i')).test(name)) {
                    $.each(handler,
                        function(i,handler){
                            if (handler.selector===selector)
                                result=true;
                        });
                }
            });
        });
        return result;
    };
    let imageId = [];
    /* ------ Select images from modal list ------------- */
    $('.modal').on('show.bs.modal', function (e) {
        $('.card').click(function () {
            let imageCard = $(this).parent().parent().clone();
            let dataId = $(this).data('id');
            let imagesFormField = $('#product-form-img').find(`div[data-id=${dataId}]`);
            /* ------- Create close button and attach event ----------------- */
            let closeButton = $(`<div class="close-image-icon" data-id="${dataId}"><i class="far fa-window-close fa-lg"></i></div>`);
            closeButton.click(function () {
                $(this).parent().parent().fadeOut('3000').remove();
            });

            $(this).find('.check-icon').toggle('1000');
            $(this).toggleClass('check-shadow');
            /* ------- Load images into create product form ------------ */
            if(imagesFormField.length === 0){
                imageCard.children().append(closeButton);
                imageCard.appendTo('#product-form-img');
            } 
        });
    });

    $('.modal').on('hide.bs.modal', function (e){
      $('.card').unbind();
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
                closeBtn.parent().parent().remove();
            },
            error:function(xhr, status, error){
               alert('Unable detach image ! ' + error)
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