function UserEdit()
{
	var pageId;
	var dataSource = propertyList;
	var pagingPlugin = null;
	
	this.Init = function()
	{
		if (typeof ValidatorWrapper === 'function')
			initValidator();
		if ($('#ddlHotelId').length > 0)
			initAutocomplete();
		
		$('.btn-save').click(function() { frm.FormSetAction('Save'); });
	}
	
	function initValidator()
	{
		var rules = { txtUsername: 'required', txtEmail: { required: true, email: true},
			txtCurrentPassword:{ required: { depends: function() { return $('#txtPassword').val() != ''; } } },
			txtPassword:{ required: { depends: function() { return frm.EditId() == '0'; } } },
			txtPasswordRepeat: { required: { depends: function() { return frm.EditId() == '0'; } },
				equalTo: '#txtPassword'}
		};
		var messages = { txtUsername: 'Introduceti numele utilizatorului', txtEmail: { required: 'Specificati emailul', email: 'Specificati un email valid' },
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
		var params = { ajax_action: 'verify_user', username: $('#txtUsername').val().trim(), email: $('#txtEmail').val().trim(), editId: editId};
		
		$.post(SITE_RELATIVE_URL  + 'ajax', params, function(dataJson) { handleAjaxResponse(dataJson, form) });
	}
	
	function handleAjaxResponse(dataJson, form)
	{
		var data = jQuery.parseJSON(dataJson);
		return displayEditResult(data, form);
	}
	
	function displayEditResult(data, form)
	{
		submitForm = true;
		if (data.userExists == '1')
		{
			$('#txtUsername').parent().append('<label class="error">Username este folosit deja</label>');
			var element = $('#txtUsername').parent().find('label');
			htmlCtl.HideElementAfterDelayJQ(element, 2500);
			submitForm = false;
		}
		if (data.emailExists == '1')
		{
			$('#txtEmail').parent().append('<label class="error">Emailul acesta este folosit deja</label>');
			var element = $('#txtEmail').parent().find('label');
			htmlCtl.HideElementAfterDelayJQ(element, 2500);
			submitForm = false;
		}
		
		if (submitForm) {
			form.submit();
		}
	}
	
	function initAutocomplete() {
		_.forEach(dataSource, function(item) {
			item.text = item.value;
			item.id = parseInt(item.id);
			item.value = null;
		});
		
		select2SetOption();
		
		pagingPlugin = new Select2PagingPlugin();
		pagingPlugin.init('#ddlHotelId', dataSource);
	};
	
	function select2SetOption() {
		var hotelId = parseInt($('#hotelId').val());
		var selectedItem = _.find(dataSource, { id: hotelId});
		if (selectedItem) {
			$('#ddlHotelId').html('<option value="' + selectedItem.id + '">' + selectedItem.text +'</select>');
		}
	}
}
var ctlUserEdit = new UserEdit();
ctlUserEdit.Init();
