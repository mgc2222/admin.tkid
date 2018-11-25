function Front() {
	function Init()
	{
		initControls();
	}
	
	function initControls() {
		$('.property-columns [data-toggle="tooltip"]').tooltip();
		
		
		$('#ads-trigger').click(function () {	
			if ($(this).hasClass('advanced')) {
				$(this).removeClass('advanced');
				$(".site-search-module").animate({
					'bottom': '-107px'
				});
				$(this).html('<i class="fa fa-plus"></i> Avansat');
				$('.slider-mask').fadeOut(500);
			} else {
				
				$(this).addClass('advanced');
				$(".site-search-module").animate({
					'bottom': '-15px'
				});
				$(this).html('<i class="fa fa-minus"></i> Basic');
				$('.slider-mask').fadeIn(500);
			}	
			return false;
		});
	}
	
	Init();
}

var objFront = new Front();