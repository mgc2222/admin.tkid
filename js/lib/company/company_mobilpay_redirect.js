function CompanyMobilpayRedirect()
{
	this.Init = function()
	{
		window.setTimeout(function() { document.frmPaymentRedirect.submit() } , 5000);
		$('.redirect').click(function() { document.frmPaymentRedirect.submit(); });
	}
}

var ctlCompanyMobilpayRedirect = new CompanyMobilpayRedirect();
ctlCompanyMobilpayRedirect.Init();