function TinyMceWrapper()
{
	var _options = {
			relative_urls : false,
			remove_script_host : false,
			convert_urls : false,
			force_br_newlines: true,
			force_p_newlines: false,
			apply_source_formatting: false,
			remove_linebreaks: false,
			convert_newlines_to_brs: true,
			forced_root_block: false,
		
			selector: 'textarea.tinymce',
			theme: 'modern',
			plugins: ['advlist autolink link image lists charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
			'save table contextmenu directionality emoticons template paste textcolor'],
			content_css: SITE_RELATIVE_URL + 'style/admin/admin.css',
			toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
		};
		
	this.Init = function(options) {
		var extendedOptions = _options;
		if (options) {
			extendedOptions = $.extend({}, _options, options);
		}
		tinymce.init(extendedOptions);
	}
}

