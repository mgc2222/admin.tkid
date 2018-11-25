//
//	class for html controls
//
function HtmlControls() 
{
	this.ToggleDisplay = function(elementId)
	{
		var element = document.getElementById(elementId);
		if (element != null)
		{
			if (element.style.display == "none")
				element.style.display = "";
			else
				element.style.display = "none";
		}
	}

	this.ShowElement = function(elementId)
	{
		var element = document.getElementById(elementId);
		if (element != null)
			element.style.display = "block";
	}

	this.HideElement = function(elementId)
	{
		var element = document.getElementById(elementId);
		if (element != null)
			element.style.display = "none";
	}
	
	this.HideElementAfterDelay = function(elementId, delay)
	{
		var element = document.getElementById(elementId);
		if (element != null)
			setTimeout(function() { element.style.display = "none"; }, delay);
	}
	
	this.HideElementAfterDelayJQ = function(element, delay)
	{
		if (element != null)
			setTimeout(function() { $(element).fadeOut() }, delay);
	}

	this.OpenWindow = function(pageUrl, pageTitle, settings)
	{
		var defaultSettings = { location:1, status:1, scrollbars:1, width:800, height:600 }
		if (settings == null) settings = defaultSettings;
		var openSettings = '';
		for (var objIndex in settings)
		{
			openSettings = objIndex + '=' + settings[objIndex] + ',';
		}
		openSettings = openSettings.substr(0, openSettings.length - 1); // remove last ',';
			
		var mywindow = window.open (pageUrl, pageTitle, openSettings);
		mywindow.moveTo(0,0);
	}

	this.OpenNewWindow = function(pageUrl)
	{
		var win=window.open(pageUrl, '_blank');
		win.focus();
	}
	
	this.LimitTextarea = function(inputId, maxChars, outputId)
	{
		var inputArea = document.getElementById(inputId);
		var outputField = document.getElementById(outputId);

		if (inputArea != null && outputField != null)
		{
			var userText = inputArea.value;
			var textLength = userText.length;
			var charsRemaining = maxChars - textLength;
			if (charsRemaining < 0) charsRemaining = 0;

			if (textLength > maxChars)
			{
				userText = userText.substring(0,maxChars);
				inputArea.value = userText;
			}
			
			outputField.innerHTML = charsRemaining;
		}
	}
	
	this.ToggleCheckboxes = function(triggerId, checkboxesClass)
	{
		var isChecked = document.getElementById(triggerId).checked;
		var arrElements = document.getElementsByTagName('input');
		var elem = null;
		for (var elemIndex = 0; elemIndex < arrElements.length; elemIndex++)
		{
			elem = arrElements[elemIndex];
			if (elem.className != null && (elem.className == checkboxesClass || elem.className.indexOf(checkboxesClass + ' ') != -1 || elem.className.indexOf(' '+checkboxesClass) != -1))
				elem.checked = isChecked;
		}
	}

	this.SetMasterCheckbox = function(triggerId, checkboxesClass)
	{
		var isChecked = true;

		$('.'+checkboxesClass).each( function() {
			if (!this.checked) { isChecked = false; return; }
		});
		
		$('#' + triggerId).attr('checked', isChecked);
	}
	
	this.ClearText = function(objInputText, defaultText)
	{
		if (defaultText == objInputText.value)
			objInputText.value = "";
	}

	this.RestoreText = function(objInputText, defaultText)
	{
		if (objInputText.value == "")
			objInputText.value = defaultText;
	}

	this.ChangeType = function(objInputText, newType)
	{
		if (objInputText.value == "")
			objInputText.type = newType;
	}

	this.JsRedirect = function(url)
	{
		if (url != '')
			window.location = url;
	}
	
	this.ScrollPage = function(locationTo)
	{
		if (locationTo == null) locationTo = 'top';
		var yLocation = 0;
		if (locationTo == 'bottom')
			yLocation = document.body.scrollHeight;
			
		window.scrollTo(0, yLocation);
	}
	
	this.VerifySelectedChecboxesCount = function(className)
	{
		if ($('.'+className+':checked').length == 0)
		{
			alert('Selectati cel putin un element');
			return false;
		}
		return true;
	}
	
	this.GetCheckListSelectedValuesByPrefixId = function(prefixId)
	{
		var ret = '';
		$('input[id^="'+prefixId+'"]').each(function()	{
			if (this.checked)
				ret += this.value+',';
		});
		if (ret != '')
			ret = ret.substr(0, ret.length - 1);
		return ret;
	}
};