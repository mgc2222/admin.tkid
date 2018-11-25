function ProductEdit()
{
	this.Init = function()
	{
		initControls();
		initValidator();
	}
	
	function initControls()
	{
		$('#txtName').blur(function() {
			strUtil.SetUrlKey('txtName','txtUrlKey', false);
		});
		
		$('#lnkRegenerateUrlKey').click(function() {
			strUtil.SetUrlKey('txtName','txtUrlKey', true);
		});

		$('#txtStatus').click(function() {
			$(this).attr('value', this.checked ? 1 : 0);
		});
		
		var tinyMceWrapper = new TinyMceWrapper();
		tinyMceWrapper.Init();
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
		var params = { ajaxAction: 'VerifyProduct', name: $('#txtName').val().trim(), urlKey: $('#txtUrlKey').val().trim(), editId: editId};
		
		frm.PostAjaxJson(SITE_RELATIVE_URL + 'products', params, function(data) { handleAjaxResponse(data, form) });
	}
	
	function handleAjaxResponse(data, form)
	{
		var submitForm = true;
		var errorMessage = '';
		if (data.categoryExists == '1')
		{
			errorMessage += 'Un produs cu acest nume exista deja<br/>';
			submitForm = false;
		}
		if (data.urlKeyExists == '1')
		{
			errorMessage += 'Un produs cu acest Url Key exista deja';
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
var objProductEdit = new ProductEdit();
var strUtil = new StringUtils();
objProductEdit.Init();