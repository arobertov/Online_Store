(function ( $ ) {
	$.fn.categoryFilter=function(selector){
		this.click( function(e) {
			var categoryValue = $(this).attr('data-filter');
			$(this).addClass('active').siblings().removeClass('active');
			if(categoryValue=="_") {
				$('.filter').show(1000);
			} else {
				$(".filter").not('.'+categoryValue).hide('3000');
            	$('.filter').filter('.'+categoryValue).show('3000');
            	$('.filter').filter(function () {
                    return $(this).data('id') === categoryValue;
                }).show('3000')
			}
			e.preventDefault();
		});

}
}( jQuery ));