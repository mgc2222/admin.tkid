function UserSettings()
{
	this.Init = function()
	{
		if (typeof ValidatorWrapper === 'function')
			initValidator();
		
		$('.btn-save').click(function() { 
			frm.FormSetAction('SaveSettings');
		});
	}
	
	function initValidator()
	{
		var rules = { 
			txtCurrentPassword:{ required: { depends: function() { return $('#txtPassword').val() != ''; } } },
			txtPassword:{ required: { depends: function() { return $('#txtCurrentPassword').val() != ''; } } },
			txtPasswordRepeat: { required: { depends: function() { return $('#txtPassword').val() != ''; } },
				equalTo: '#txtPassword'}
		};
		var messages = { 
			txtCurrentPassword:'Introduceti parola actuala',
			txtPassword:'Introduceti parola',
			txtPasswordRepeat:{ required: 'Repetati parola', equalTo: 'Parola repetata nu corespunde'}
		};

		var vW = new ValidatorWrapper();
		// vW.InitValidator('mainForm', rules, messages, function() { verifyAjax(); }, function(label) { successFunction(label); } );
		vW.InitValidator('mainForm', rules, messages, function(form) { verifyAjax(form); }, null, null, 'span' );
	}
	
	function verifyAjax(form)
	{
		var editId = frm.EditId();
		var params = null;
		if ($('#txtCurrentPassword').val() != '')
		{
			params = { ajax_action: 'verify_user_password', editId: editId, password: $('#txtCurrentPassword').val() };
			$.post(SITE_RELATIVE_URL  + 'ajax', params, function(dataJson) { handleAjaxResponse(dataJson, form) });
		}
		else
			frm.FormSubmitAction('SaveSettings');
	}
	
	function handleAjaxResponse(dataJson, form)
	{
		var data = jQuery.parseJSON(dataJson);
		return displaySettingsResult(data, form);
	}
	
	function displaySettingsResult(data, form)
	{
		var submitForm = true;
		if (data.passwordMatch != '1')
		{
			$('#txtCurrentPassword').parent().append('<label class="error">Parola nu corespunde cu cea existenta</label>');
			var element = $('#txtCurrentPassword').parent().find('label');
			htmlCtl.HideElementAfterDelayJQ(element, 2500);
			submitForm = false;
		}
		
		if (submitForm)
			form.submit();
	}	
}
var ctlUserSettings = new UserSettings();
ctlUserSettings.Init();
