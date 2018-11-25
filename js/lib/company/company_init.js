var frm = null,
	htmlCtl = null;

if (typeof FormClass === 'function')
	frm = new FormClass();
	
if (typeof FormClass === 'function')
	htmlCtl = new HtmlControls();

$('.dropdown').dropit();