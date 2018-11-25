function AdminJobs()
{
	this.Init = function()
	{
		if (typeof SortableWrapper === 'function')
		{	
			initSortable();
		}

		if (typeof PagingClass === 'function')
		{
			var objPagingClass = new PagingClass();
			objPagingClass.InitDualPaging();
		}
		
		if ($('#txtTitle').length > 0) // if edit
			initEdit();
		else // jobs list
			initControls();
	}
	
	function initEdit()
	{
		initDatePickers();
		initValidator();
		initControls();
	}
	
	function initSortable()
	{
		var objSortableWrapper = new SortableWrapper();
		var categoryId = document.getElementById('hid_CategoryId').value;
		if (categoryId == 0 || categoryId == '')
			return;

		var queryParams = { cid: categoryId }
		var options = { tablename:'sortable', urlServer:'articles.php', colorRowOdd:'eeeeee', colorRowEven:'FEFEFE', queryParams:queryParams};
		objSortableWrapper.SetOptions(options);
		objSortableWrapper.Init();
		
		$('.grid_view tr').addClass('allow_drag'); // class to show hand cursor
	}
	
	function initDatePickers()
	{
		var objRDW = new RomanianDatepickerWrapper();
		objRDW.InitRomanianDatePickersFromTo('txtDateStart', 'txtDateEnd', true);
		if ($('#txtDateEndSearch').length > 0)
			objRDW.InitRomanianDatePickersFromTo('txtDateStartSearch', 'txtDateEndSearch', true);
			
		$('#txtDateStart').datepicker( 'option', { onClose: function (dateText, inst) {$(this).trigger('blur');} });
		$('#txtDateEnd').datepicker( 'option', { onClose: function (dateText, inst) { $(this).trigger('blur');}  });
	}
		
	function initValidator()
	{
		addDateFormatMethod();
		
		var rules = { txtName: 'required', txtEmail: { required: true, email: true}, txtDateStart: { required: true, dateFormat: true }, txtDateEnd: { required: true, dateFormat: true } };
		var messages = { txtName: 'Introduceti numele companiei', txtEmail: { required: 'Specificati emailul', email: 'Specificati un email valid' }, txtDateStart: { required: 'Alegeti data de inceput', dateFormat: 'Specificati o data corecta'},	txtDateEnd: { required: 'Alegeti data de final', dateFormat: 'Specificati o data corecta'}	};
		
		var vW = new ValidatorWrapper();
		vW.InitValidator('mainForm', rules, messages, null, null, null, 'span' );
	}
	
	function initControls()
	{
		$('.publish_job').click(function() { showPublishForm($(this).attr('data')); });
		$('.publish_job_package').click(function() { publishJob($(this).attr('data')); });
		$('.save_job').click(function() { frm.FormSaveData(); });
	}
	
	function showPublishForm(jobId)
	{
		
		if (!confirm('Esti sigur ca vrei sa publici aces job?'))
			return;

		$('.publish_job_package').attr('data', jobId); // set selected job
		
		$.post('jobs.php', { ajax_action: 'get_publish_data', jobId: jobId }, function(dataJson) {
			var data = jQuery.parseJSON(dataJson);
			if (data.packagesCount > 1)
			{
				var dataContent = jQuery.base64.decode(data.content);
				// show form to select what type of announce wants to add
				$('#packages-types').html(dataContent);
				$.colorbox( { inline:true, width:"460px", height:"300px", 
					'href':$('#job-publish'), 
					onClosed:function(){ 
						$('#job-publish').hide();
					} 
				});
				
				$('#job-publish').show();
			}
			else
			{
				displayPublishMessage(data);
			}
		});		
	}
	
	function publishJob(jobId)
	{
		var packageId = $('input[name="rdPackages"]:checked').val();
		$.post('jobs.php', { ajax_action: 'publish_job', jobId: jobId, packageId: packageId }, function(dataJson) {
			var data = jQuery.parseJSON(dataJson);
			displayPublishMessage(data);
		});
	}
	
	function displayPublishMessage(data)
	{
		$('.result_message').removeClass('error success').addClass(data.status);
		$('.result_message').html(data.message).show();
		var jobId = $('.publish_job_package').attr('data');
		$('.publish_job[data="'+jobId+'"]').remove(); // remove publish link
		$.colorbox.close();
		$('html, body').animate({scrollTop:top}, 300);
		if (document.location.href.indexOf('jobs.php') > 0)
			setTimeout(function() { document.location.reload()} , 2000);
	}
	
	function addDateFormatMethod()
	{
		$.validator.addMethod("dateFormat",
		function(value, element) {
			return value.match(/^\d{2}?-\d{2}?-\d{4}$/);
		},
		"Alegeti o data corecta.");
	}
}


var strUtil = new StringUtils();
var objAdminJobs = new AdminJobs();

objAdminJobs.Init();