function NumberBox()
{
	// selector: 'input.number'
	this.Init = function(selector)
	{
		jQuery(selector).keydown(function(event) 
		{  
			// Allow: backspace, delete, tab and escape
			if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 
				 // Allow: Ctrl+A
				(event.keyCode == 65 && event.ctrlKey === true) || 
				 // Allow: home, end, left, right
				(event.keyCode >= 35 && event.keyCode <= 39)) {
					 // let it happen, don't do anything
				return;
			}
			else 
			{
				// Ensure that it is a number and stop the keypress
				if ( event.shiftKey|| (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) 
				{
					// allow only one dot .
					if (!(this.value.indexOf('.') == -1  && (event.keyCode == 190 || event.keyCode == 110) ))
					{
						event.preventDefault();
					}
				}
			}
		});
	}
}