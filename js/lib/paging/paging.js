function PagingClass()
{
	this.Init = function(ddlPageSelectId, ddlItemsPageId, pageSelectKey, itemsPageKey)
	{
		var location = document.location.href;
		if (ddlPageSelectId != null)
		{
			if ($('#'+ddlPageSelectId).length > 0)
			{
				$('#'+ddlPageSelectId).change(function() {
					var val = $(this).val();
					location = addQueryParam(location, pageSelectKey, val);
					document.location.href = location;
				});
			}
		}
		
		if (ddlItemsPageId != null)
		{
			if ($('#'+ddlItemsPageId).length > 0)
			{
				$('#'+ddlItemsPageId).change(function() {
					var val = $(this).val();
					location = addQueryParam(location, itemsPageKey, val);
					document.location.href = location;
				});
			}
		}
	}
	
	this.InitDualPaging = function()
	{
		this.Init('ddlPageSelect1', 'ddlItemsPage1', 'page', 'itemspage');
		this.Init('ddlPageSelect2', 'ddlItemsPage2', 'page', 'itemspage');
	}
	
	this.InitSinglePaging = function()
	{
		this.Init('ddlPageSelect', 'ddlItemsPage', 'page', 'itemspage');
	}
	
	function getQueryParams()
	{
		var pos = document.location.href.lastIndexOf('/');
		if (pos > 0)
			return document.location.href.substr(pos + 1);
		else return '';
	}
	
	function addQueryParam(location, paramKey, paramValue)
	{
		var regex = new RegExp('(/|;)'+paramKey+'=(\\d+)', "gi");
		var regexResult = regex.exec(location);
		if (regexResult) {
			location = location.replace(regex, '$1' + paramKey + '='+paramValue);
		}
		else
		{
			var lastChar = location.charAt(location.length - 1);
			var equalCharPos = location.indexOf('=');
			if (equalCharPos > 0) {
				location += ';';
			}
			else if (lastChar != '/') {
				location += '/';
			}
			
			location += paramKey+'='+paramValue;
		}
		
		return location;
	}
};