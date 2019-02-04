function setWindowMinHeight()
{
	var wH = $(window).height();
	var headerH = $('.header').outerHeight();
	var footerH = $('.footer').outerHeight();
	var cssHeight = wH - headerH - footerH;
	$('.page_wrapper').css('min-height', cssHeight + 'px');
}

function initGridDelete()
{
	if ($('.grid_wrapper').length > 0)
	{
		$('.delete-item').click(function(){
			var id = $(this).data('id');
			frm.FormValidateDelete(id);
		});
	}
}

function setLanguageMenuTrigger(){
	$('#admin-header-select-language').on("click", function (e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    })
}

function initToastr()
{
	if (typeof toastr === 'function')
	{
		toastr.options = {
		  "closeButton": false,
		  "debug": false,
		  "newestOnTop": false,
		  "progressBar": false,
		  "positionClass": "toast-top-right",
		  "preventDuplicates": false,
		  "onclick": null,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "5000",
		  "extendedTimeOut": "1000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
		}
	}
}

var frm = null,
	htmlCtl = null;

if (typeof FormClass === 'function')
	frm = new FormClass();
	
if (typeof FormClass === 'function')
	htmlCtl = new HtmlControls();

initGridDelete();
initToastr();
setWindowMinHeight();
setLanguageMenuTrigger();