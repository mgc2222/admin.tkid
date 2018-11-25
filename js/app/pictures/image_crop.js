$().ready(function() {
	// Remember to invoke within jQuery(window).load(...)
			// If you don't, Jcrop may not initialize properly

	$('#cropbox').Jcrop({
		onChange: showCoords,
		onSelect: showCoords
		// ,aspectRatio: 1
	});

	// Our simple event handler, called from onChange and onSelect
	// event handlers, as per the Jcrop invocation above
	function showCoords(c)
	{
		jQuery('#x').val(c.x);
		jQuery('#y').val(c.y);
		jQuery('#x2').val(c.x2);
		jQuery('#y2').val(c.y2);
		jQuery('#w').val(c.w);
		jQuery('#h').val(c.h);
	};
});

function checkCoords()
{
	if(jQuery('#x').val() =="" || jQuery('#y').val() =="" || jQuery('#x2').val() =="" || jQuery('#y2').val() ==""
		|| jQuery('#w').val() =="" || jQuery('#h').val() =="")
	{
		alert("Selectati o portiune din imagine sau toata imaginea");
		return false;
	}
	return true;
};