function Pictures()
{
	var trans = { delete_images_all: 'Confirmati stergerea tuturor imaginilor din aceasta sectiune?', delete_image: 'Confirmati stergerea imaginii?'};
	this.Init = function()
	{
		//initToolTips();
		//initSortable();
		initControls();		
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
		$('.delete').click(function() {
			if (confirm(trans['delete_image']))
				frm.FormSubmitAction('Delete', $(this).attr('data'));
		});
		
		$('.delete_all').click(function() {
			if (confirm(trans['delete_images_all']))
				frm.FormSubmitAction('DeleteProductImages');
		});

		$('.delete_app_image').click(function() {
			if (confirm(trans['delete_image']))
				frm.FormSubmitAction('DeleteAppImage', $(this).attr('data'));
		});
		
		$('.delete_app_all').click(function() {
			if (confirm(trans['delete_images_all']))
				frm.FormSubmitAction('DeleteAppAllImages');
		});
		$('#categoryId').change(function() {
			frm.FormSubmitAction('FilterResults');
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
}

var ctlPictures = new Pictures();
ctlPictures.Init();