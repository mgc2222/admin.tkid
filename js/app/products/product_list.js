function ProductList()
{
	this.Init = function()
	{
		initControls();
	}
	
	function initControls()
	{
		var paging = new PagingClass();
		paging.InitSinglePaging();
	}	
}
var ctlProductList = new ProductList();
ctlProductList.Init();