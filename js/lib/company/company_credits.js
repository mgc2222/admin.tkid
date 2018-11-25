function CompanyCredits()
{
	this.Init = function()
	{
		var nBox = new NumberBox();
		nBox.Init('input.number');
		
		jQuery('input.number').focus(function(event) {
			updateTotals(this.id);
		});
		
		jQuery('input.number').keyup(function(event) {
			updateTotals(this.id);
		});
		
		$(".inline").colorbox({inline:true, width:"550px"});
		
		$(document).bind('cbox_complete', function(){
		  var elementId = $.colorbox.element().parent().find('input').first().attr('id');
		  updateTotals(elementId);
		  completeDetailsDisplay(elementId);
		});
		
		$('#btnPaymentCard').click(function() {
			frm.FormSubmitAction('Order', 'card');
		});
		
		$('#btnPaymentTransfer').click(function() {
			frm.FormSubmitAction('Order', 'transfer');
		});
	}
	
	function getCreditsItems(packageId)
	{
		var arrCreditsItems = new Array();
		
		$('input[id^="hidPackage_'+packageId+'_"]').each(function() {
			var arrElementId = this.id.split('_');
			var packId = arrElementId[1];
			var rangeId = arrElementId[2];
			
			var arrPrice = $(this).val().split('_');
			var min = parseInt(arrPrice[0]);
			var max = parseInt(arrPrice[1]);
			var price = parseInt(arrPrice[2]);
			
			var packageItem = { id: packId, rangeId: rangeId, price: price, min:min, max:max };
			arrCreditsItems.push(packageItem);
			
		});
		
		return arrCreditsItems;
	}
	
	function updateTotals(elementId)
	{
		var amountSaved = 0;
		var amountPayed = 0;
		var basePrice = 0;
		var maxPrice = 0;
		var minPrice = 10000;
		
		var credits = $('#'+elementId).val();
		if (credits <= 0 || credits == '')
		{
			updateVisual(amountSaved, amountPayed);
			return;
		}
		
		var arrElementId = elementId.split('_');
		var packageId = arrElementId[1];
		
		var arrCreditsItems = getCreditsItems(packageId);
		
		for (var packageIndex = 0; packageIndex < arrCreditsItems.length; packageIndex++)
		{
			var creditItem = arrCreditsItems[packageIndex];
			
			if (creditItem.price > maxPrice)
				maxPrice = creditItem.price;
			
			if (creditItem.price < minPrice)
				minPrice = creditItem.price;

			if (credits >= creditItem.min && credits <= creditItem.max)
			{
				basePrice = creditItem.price;
				break;
			}
		}
		// if basePrice not found (i.e.: 200 credits bought and our limit is 10), then take the lowest price found
		if (basePrice == 0)
			basePrice = minPrice;
		
		amountPayed = credits * basePrice;
		amountSaved = credits * (maxPrice - basePrice);
		
		updateVisual(amountSaved, amountPayed);
	}
	
	function updateVisual(amountSaved, amountPayed)
	{
		$('#moneySaved').html(amountSaved);
		$('#moneyPayed').html(amountPayed);
	}
	
	function completeDetailsDisplay(elementId)
	{
		var amountPayed = $('#moneyPayed').html();
		if (amountPayed == '0')
		{
			$('#comanda-details').html('<div class="system_message error">Nu ati selectat credite pentru comanda !</div>');
			$('.plata-card').hide();
			$('.transfer-bancar').hide();
		}
		else
		{
			var credits = $('#'+elementId).val();
			var packageName = getPackageName(elementId);
			$('#comanda-details').html('Comanda: Pachet ' + packageName + ', ' + credits + ' credite  = ' + amountPayed + ' Lei');
			$('.plata-card').show();
			$('.transfer-bancar').show();
			$('#hidPackage').val(elementId);
		}
		
		$('.inline').colorbox.resize();
	}
	
	function getPackageName(elementId)
	{
		var arrId = elementId.split('_');
		var packageId = arrId[1];
		return $('#hidPackageName_'+packageId).html();
	}
}

var companyCredits = new CompanyCredits();
companyCredits.Init();