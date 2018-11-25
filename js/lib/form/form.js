function FormClass()
{
	var formTriggerElement;
	var _this = this;
	
	// this will return the element which triggered the Enter key event
	this.GetTriggerElement = function() { return formTriggerElement; }
	
	// call this function in order to capture the element which triggers 
	this.CaptureFormTriggerElement = function()
	{
		document.onkeydown = function(ev)
		{
			if (typeof ev == 'undefined' && window.event) { ev = window.event; }
			
			if (ev.keyCode == 13)
			{
				if (document.all)
				{
					formTriggerElement = event.srcElement;
				}
				else
				{	
					formTriggerElement = ev.originalTarget;
					if (!formTriggerElement)
						formTriggerElement = ev.target;
				}
			}
		}
	}
	
	// submit form with id = formId, and call callback function for verification, if provided
	this.SubmitForm = function(formId, callbackVerify)
	{
		var verifyStatus = true;
		if (callbackVerify != null)
			verifyStatus = callbackVerify();
		if (verifyStatus)
			jQuery('#'+formId).submit();
	}
	
	//
	//	submit an ajax form
	//
	// wrapperId: name of the element
	// method: 'get' or 'post'
	// urlServer: address of the server file to call
	// extraParams: 
	// callbackVerify: name of the element
	// callbackResult: name of the element
	this.SubmitFormAjax = function(wrapperId, method, urlServer, extraParams, callbackVerify, callbackResult)
	{
		var verifyStatus = true;
		if (callbackVerify != null)
			verifyStatus = callbackVerify();
		if (verifyStatus)
		{
			var params = getFormData(wrapperId);
			if (extraParams != null)
				copyObjectData(extraParams, params);
				
			if (method == 'get')
				jQuery.get(urlServer, params, function(data) { if (callbackResult != null) callbackResult(data); });
			else if (method == 'post')
				jQuery.post(urlServer, params, function(data) { if (callbackResult != null) callbackResult(data); });
		}
	}
	
	// attach this to a text box, to trigger a button click when Enter is pressed
	this.TriggerButtonClickOnEnter = function(e, buttonId)
	{
        // look for window.event in case event isn't passed in
        if (typeof e == 'undefined' && window.event) { e = window.event; }
        if (e.keyCode == 13)
        {
            document.getElementById(buttonId).click();
        }
	}
	
	// verify if Enter key was pressed
	// attach this to a textbox: onkeydown="IsEnterKey(event)";
	this.IsEnterKey = function(ev)
	{
		var e = window.event ? window.event : ev;
		var iKeyCode = e.keyCode; // ? e.keyCode: e.charCode;
		
		return (iKeyCode == 13);
	}
	
	// ask for confirmation and set the Delete action for form
	this.FormValidateDelete = function(param)
	{
		if (window.confirm("Va rugam sa confirmati stergerea elementului selectat ")) {
			_this.FormSubmitAction('Delete',param);
		}
	}

	// set the action, params and form id hidden variables and submit the form
	this.FormSetAction = function(action, params)
	{
		document.getElementById('sys_Action').value = action;
		if (params == null) params = '';
		document.getElementById('sys_Params').value = params;
	}
	
	// set the action, params and form id hidden variables and submit the form
	this.FormSubmitAction = function(action, params, formId)
	{
		_this.FormSetAction(action, params)
		if (formId == null) formId = 'mainForm';
		document.forms[formId].submit();
	}
	
	this.PostAjaxJson = function(urlServer, data, callbackDone, callbackFail)
	{
		var promise = $.ajax({
			type: "POST",
			url: urlServer,
			data: JSON.stringify(data),
			contentType: "application/json; charset=utf-8",
			dataType: "json"
		}).done(function(response) {
			if (callbackDone)
				callbackDone(response);
		})
		.fail(function(response) {
			if (callbackFail)
				callbackFail(response);
		});
	}

	// triggers the validation and submit (if validation is ok) of a form
	this.FormSaveData = function(formId)
	{
		if (formId == null) formId = 'mainForm';
		var submitForm = true;
		if (typeof $("#"+formId).valid === 'function')
		{
			submitForm = ($("#"+formId).valid())  // test the form for validity
		}
		
		if (submitForm)
			$("#"+formId).submit();
	}

	// delete selected elements
	// if className not provideded, default is : multi_checkbox
	this.FormDeleteSelected = function(className)
	{
		if (className == null) className = 'multi_checkbox';
		
		if (!verifySelectedChecboxesCount(className)) return;
		if (confirm('Confirmati stergerea elementelor selectate'))
			_this.FormSubmitAction('DeleteSelected', '');
	}

	// ask for confirmation and the set the form action to DeleteAll
	this.FormDeleteAll = function()
	{
		if (confirm('Confirmati stergerea tuturor elementelor'))
			if (confirm('Esti absolut sigur ca vrei sa stergi toate elementele?'))
				_this.FormSubmitAction('DeleteAll', '');
	}
	
	// shows an alert message
	// selector : '#elementId' or '.elementClass' .. or any jquery selector
	// if message is not null, will overwrite the html of the selector element 
	// if delay is specified, the message will dissapear after the specified delay time
	// callback : a function which will be called after the delay time
	this.FormSetAlert = function(selector, status, message, delay, callback)
	{
		$(selector).removeClass('error success'); // clean error status 
		$(selector).addClass(status);
		
		if (message != null)
			$(selector).html(message).show();
		
		if (delay != null)
		{
			setTimeout(function() { 
				$(selector).fadeOut(); 
				if (callback != null)
					callback();
			}, delay);
		}
	}
	
	this.GridSortColumn = function(tableName, columnName)
	{
		var objSortClass = new SortGridColumnClass();
		objSortClass.SortColumn(tableName, columnName);
		_this.FormSubmitAction('SortColumn',tableName);
	}
	
	this.EditId = function()
	{
		 return ($('#sys_EditId').length > 0)? $('#sys_EditId').val() : 0;
	}
	
	this.JsRedirect = function(url)
	{
		if (url != '') {
			window.location = url;
		}
	}

	// verify if any checkbox which has "className" , was selected
	function verifySelectedChecboxesCount(className)
	{
		if ($('.'+className+':checked').length == 0)
		{
			alert('Selectati cel putin un element');
			return false;
		}
		return true;
	}

	// get the inputs and select values of a form and create an object with these params
	function getFormData(wrapperId)
	{
		var params = { ajax_action: form_submit };
		jQuery('#'+wrapperId+' input').each(function() { params[this.id] = this.value; });
		jQuery('#'+wrapperId+' select').each(function() { params[this.id] = jQuery(this).val(); });
		
		return params;
	}
	
	// copy the attributes from an object to another
	function copyObjectData(objSource, objDestination)
	{
		if (null == objSource || "object" != typeof objSource) return objSource;
		for (var attr in objSource) 
		{
			if (objSource.hasOwnProperty(attr)) 
				objDestination[attr] = objSource[attr];
		}
	}
};