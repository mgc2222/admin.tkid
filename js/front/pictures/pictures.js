function Pictures()
{
	var trans = { delete_images_all: 'Confirmati stergerea tuturor imaginilor din aceasta sectiune?', delete_image: 'Confirmati stergerea imaginii?'};
	this.Init = function()
	{
		initToolTips();
		initSortable();
		initControls();
		
		if (typeof AutoCompleteWrapper === 'function')
			initAutocomplete();
	}
	
	function initToolTips()
	{
		$('.image-tooltip-target').each(function() { 
			var thisRefTitle = $(this).attr("title");
			$(this).tooltip({ 
				delay: 100, 
				track:true,
				showURL: false, 
				bodyHandler: function() { 
					
					var content = unescape(thisRefTitle)
					return content; //$("<img/>").attr("src", content); 
				} 
			}) 
		});
	}
	
	function initSortable()
	{
        $( "#holderSortable").sortable( {
			start: function( event, ui ) { 
				imgDragged = ui.item.find('.image-tooltip-target');
				imgDraggedOnclick = imgDragged.attr('onclick');
				imgDragged.removeAttr('onclick');
				$('#tooltip').hide(); // hide tooltip
			},
			update: function( event, ui ) { 
				//$('#tooltip').show();  // show tooltip
				// save the positions
				//imgDragged = ui.item.find('.image-tooltip-target');
				saveImagesOrder();
			},
		});
        $( "#holderSortable" ).disableSelection();
	}
	
	function initControls()
	{
		$('#ddlRoomType').change(function() {
			frm.FormSubmitAction('ChangeRoomType', '1')
		});
		
		$('.delete').click(function() {
			if (confirm(trans['delete_image']))
				frm.FormSubmitAction('Delete', $(this).attr('data'));
		});
		
		$('.delete_all').click(function() {
			if (confirm(trans['delete_images_all']))
				frm.FormSubmitAction('DeletePropertyImages');
		});
	}

	function saveImagesOrder()
	{
		// get ids of images (they will be in the order that user placed them)
		var imgIds = '';
		$('input[id^="hidImageId_"]').each(function() { 
			imgIds += $(this).val() + ',';
		});
		imgIds = imgIds.substr(0, imgIds.length - 1);
		
		// make a save
		var params = { ajaxAction:'save_order', img_ids:imgIds };
		var url = SITE_RELATIVE_URL + 'pictures';
		frm.PostAjaxJson(url, params, function() { imgDragged.attr('onclick', imgDraggedOnclick);  } )
	}
	
	function initAutocomplete()
	{
		var autoComplete = new AutoCompleteWrapper();
		if ($('#txtSideSearchHotel').length > 0)
			autoComplete.InitAutoComplete('txtSideSearchHotel', 'txtSideSearchHotelId', propertyList, function() { selectCallbackFunction(); });
	}
	
	function selectCallbackFunction()
	{
		$('#frmSearch').submit();
	}
}

var ctlPictures = new Pictures();
ctlPictures.Init();