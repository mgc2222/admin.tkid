function CompanyJobDetails()
{
	var triggerElement = null;
	this.Init = function()
	{
		if (typeof PagingClass === 'function')
		{
			var objPagingClass = new PagingClass();
			objPagingClass.InitDualPaging();
		}
		initControls();
	}
	
	function initControls()
	{
		$('.pentru-interviu, .de-analizat, .respins, .fara-status').click(function() { 
			if ($(this).hasClass('filter')) return;  
			triggerElement = this;
			var cvId = $(this).attr('data'); 
			var cssClass = $(this).attr('class');
			setCvStatus(cvId, cssClass); 
		});
		
		$('.filter').click(function() { applyFilter($(this).attr('class')); });
	}
	
	function setCvStatus(cvId, status)
	{
		if (!confirm('Esti sigur ca vrei sa setezi statusul acestui CV?'))
			return;

		$.post('actions.php', { ajax_action: 'set_cv_status', cvId: cvId, status:status }, function(dataJson) {
			var data = jQuery.parseJSON(dataJson);
				displayActionResult(data);
		});		
	}
	
	function applyFilter(cssClass)
	{
		var arrClass = cssClass.split(' ');
		var statusClass = arrClass[1];
		var id = getParameterByName('id');
		var sc = getParameterByName('sc');
		var loc = 'job_details.php?id='+id+'&status='+statusClass;
		if (sc != '')
			loc += '&sc='+sc;

		document.location = loc;
	}
	
	function displayActionResult(data)
	{
		$('.result_message').removeClass('error success').addClass(data.status);
		$('.result_message').html(data.message).show();
		if (data.status == 'success')
			$('#status_'+data.id).removeAttr('class').addClass(data.css_class);
		
		if (triggerElement != null)
		{
			$('.result_message').width(180);
			var pos = getScreenCenter('.result_message');
			$('.result_message').offset(pos);
		}
				
		setTimeout(function() { $('.result_message').fadeOut('slow')} , 3000);
	}
	
	function getScreenCenter(element)
	{
		var obj = {top:0, left:0};
		obj.top = Math.max(0, (($(window).height() - $(element).outerHeight()) / 2) +  $(window).scrollTop());
		obj.left = Math.max(0, (($(window).width() - $(element).outerWidth()) / 2) + $(window).scrollLeft());
		return obj;
	}
	
	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
}

var objCompanyJobDetails = new CompanyJobDetails();
objCompanyJobDetails.Init();