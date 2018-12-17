function EventsEdit()
{
	this.Init = function()
	{
        initDateTimePickers();
		initControls();
		initValidator();
	}
	
	function initControls()
	{
		$('#txtName').change(function() {
			strUtil.SetUrlKey('txtName','txtUrlKey', true);
		});
		
		$('#lnkRegenerateUrlKey').click(function() {
			strUtil.SetUrlKey('txtName','txtUrlKey', true);
		});
		
		var tinyMceWrapper = new TinyMceWrapper();
		tinyMceWrapper.Init();
	}

    function initDateTimePickers()
    {
        /*var objRDW = new RomanianDateTimePickerWrapper();
        objRDW.InitRomanianDateTimePickersFromTo('txtDateStart', 'txtDateEnd', true);
        if ($('#txtDateEndSearch').length > 0)
            objRDW.InitRomanianDateTimePickersFromTo('txtDateStartSearch', 'txtDateEndSearch', true);*/

        $('#txtDateStart').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'DD/MM/YY hh:mm A'
            }
        });
        $('#txtDateEnd').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'DD/MM/YY hh:mm A'
            }
        });
    }
	
	function initValidator()
	{
		var rules = { txtName: 'required', txtUrlKey: 'required'};
		var messages = { txtName: 'Completati campul Nume', txtUrlKey: 'Completati campul Url Key' };

		var vW = new ValidatorWrapper();
		vW.InitValidator('mainForm', rules, messages, function(form) { verifyAjax(form); }, null, null, 'span' );
	}
	
	function verifyAjax(form)
	{
		var editId = ($('#sys_EditId').length > 0)?$('#sys_EditId').val():0;
		var params = { ajaxAction: 'VerifyEvent', name: $('#txtName').val().trim(), urlKey: $('#txtUrlKey').val().trim(), editId: editId};
		
		frm.PostAjaxJson(SITE_RELATIVE_URL + 'events', params, function(data) { handleAjaxResponse(data, form) });
	}
	
	function handleAjaxResponse(data, form)
	{
		var submitForm = true;
		var errorMessage = '';
		if (data.eventsExists == '1')
		{
			errorMessage += 'Un eveniment cu acest nume exista deja. Alegeti alt nume<br/>';
			submitForm = false;
		}
		if (data.urlKeyExists == '1')
		{
			errorMessage += 'Un eveniment cu acest Url Key exista deja. Alegeti alt Url Key';
			submitForm = false;
		}
		
		if (submitForm) {
			form.submit();
		}
		else {
			toastr.error(errorMessage);
		}
	}
}
var objEventsEdit = new EventsEdit();
var strUtil = new StringUtils();
objEventsEdit.Init();