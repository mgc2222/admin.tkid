function SortableWrapper()
{
	var sortStartIndex = 0;
	var options;
	var thisRef;
	
	// opt : object who must contains following attributes: { tablename, urlServer, colorRowOdd, colorRowEven, queryParams } 
	// queryParams = optional query parameters to be transmited
	this.SetOptions = function(opt)	{ options = opt; }
	
	this.Init = function()
	{
		thisRef = this;
		$( "#"+options.tablename+" tbody" ).sortable({
			helper: fixHelper,
			start: function(event, ui) {
				sortStartIndex = $(ui.item).index();
			},
			update: function(event, ui) {
				var pkId = $(ui.item).find('[id^="hidSortPK_"]').val();
				var endIndex = $(ui.item).index();
				// get the previous item id
				var prevId = 0;
				if ($(ui.item).prev().length > 0 && $(ui.item).prev().find('[id^="hidSortPK_"]').length > 0)
					prevId = $(ui.item).prev().find('[id^="hidSortPK_"]').val();
				// get the next item id
				var nextId = 0;
				if ($(ui.item).next().length > 0 && $(ui.item).next().find('[id^="hidSortPK_"]').length)
					nextId = $(ui.item).next().find('[id^="hidSortPK_"]').val();

				var categoryId = getParameterByName('id');
				var params = { ajaxAction: 'change_order', pkId: pkId, prevId:prevId, nextId:nextId, startIndex:sortStartIndex, endIndex:endIndex};

				if (options.queryParams != null)
					copyObjAttributes(options.queryParams, params);

				frm.PostAjaxJson(options.urlServer, params,
					function(data) {
						if (data.status != "success")
						{				  
							$("#"+options.tablename+" tbody" ).sortable("cancel");
						}
						else if (data.refresh) // if refresh required
						{
							$("#sort_holder").html(jQuery.base64.decode(data.content));
							thisRef.Init(); // reinitialize
						}
						else
						{
							redoRowsColor(); // restore rows colors
							toastr[data.status]('Ordinea a fost modificata');
						}
				});
			}
		});
		$( "#"+options.tablename+" tbody" ).disableSelection();
	};
	
	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};

	function redoRowsColor()
	{
		$("#"+options.tablename+" tbody").children("tr").each(function(index) {
			var color = ((index % 2) == 0)? "#"+options.colorRowOdd : "#"+options.colorRowEven;
			$(this).css("background-color", color);
		});
	}
	
	function getParameterByName(name) 
	{
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	
	function copyObjAttributes(objSource, objDestination) {
		if (null == objSource || "object" != typeof objSource) return;
		for (var attr in objSource) {
			if (objSource.hasOwnProperty(attr)) objDestination[attr] = objSource[attr];
		}
	}
	
	function showObjectAttr(obj)
	{
		var msg = '';
		for (var x in obj)
			msg += x+':'+obj[x]+';';
		alert(msg);
	}
	
	this.GetParameterByName = function(name) { return getParameterByName(name); }
	
};