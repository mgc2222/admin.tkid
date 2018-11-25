function AutoCompleteWrapper()
{
	var autoCompleteItemsCount = 0;
	var autoCompleteItemsMaxCount = 8;
		
	this.InitAutoComplete = function(textId, hiddenId, source, selectCallbackFunction, closeCallbackFunction, autocompleteCallbackFunction, searchCallbackFunction)
	{
		if (document.getElementById(textId) == null) return;

		jQuery("#"+textId).autocomplete( { source:source, 
				minLength: 1,
				delay: 100,
				selectFirst: true,
				search: function(e, ui) {
					autoCompleteItemsCount = 0;
					if (searchCallbackFunction != null)
						searchCallbackFunction(ui.item);
				},
				select: function(e, ui) { 
					$('#'+hiddenId).val(ui.item.id);
					if (selectCallbackFunction != null)
						selectCallbackFunction(ui.item);
				},
				close: function() {
					var itemText = $("#"+textId).val();
					if (closeCallbackFunction != null)
						closeCallbackFunction(itemText);
					
					displayText = itemText.replace('%3Cspan%20style%3D%22color%3Ared%22%3E%20', ' ').replace('%3C%2Fspan%3E', '');
					displayText = replaceEncodedSpaces(displayText);
					$("#"+textId).val(displayText);
				}
			}).data( "autocomplete" )._renderItem = function( ul, item ) {
					if (autocompleteCallbackFunction != null)
						return autocompleteCallbackFunction(ul, item);
						
					autoCompleteItemsCount++;
					// if (autoCompleteItemsCount == autoCompleteItemsMaxCount)
						// return $( "<li></li>" )
						// .data( "item.autocomplete", item )
						// .append( '<a href="javascript:;" onclick="autoCompleteItemsMaxCount += 8; return false;">Show more results</a>' )
						// .appendTo( ul );
					// else 
					if (autoCompleteItemsCount > autoCompleteItemsMaxCount)
						return '';
					else
					{
						return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + itemHighlight(decodeURI(item.value), $("#"+textId).val()) + "</a>" )
						.appendTo( ul );
					}
				};
	}
	
	this.HighlightItem = function(s, t, decode)
	{
		if (decode)
			s = decodeURI(s);
		return itemHighlight(s, t);
	}
	
	function itemHighlight(s, t) 
	{
		// var displayText = s.replace(/(<([^>]+)>)/ig,"");
		var matcher = new RegExp("("+$.ui.autocomplete.escapeRegex(t)+")", "ig" );
		
		// check if contains the tag <span, if so, remove it and compare only with the actual word
		var pos = s.indexOf('<span');
		var spn = '';
		if (pos > 0)
		{
			spn = s.substr(pos);
			s = s.substr(0, pos);
		}

		return s.replace(matcher, "<strong>$1</strong>") + spn;
		// return s.replace(matcher, "<strong>$1</strong>");
	}
	
	function decodeURI(str)
	{
		return decodeURIComponent((str + '').replace(/\+/g, '%20'));
	}

	function replaceEncodedSpaces(str)
	{
		return str.replace(/%20/g, ' ');
	}
}