function LanguageEdit()
{
	this.Init = function()
	{
		initTranslation();
		initValidator();
	}
	
	function initTranslation()
	{
		var options = {  lng: 'ro', fallbackLng: 'ro', resGetPath: 'langs/__lng__/__ns__.json', lngWhitelist: ['ro', 'en', 'fr', 'de'] };
		i18n.init(options).done(function () {
			$('.page_content').i18n();
		});
	}
	
	function initValidator()
	{
		var rules = { 
			txtName: 'required',
			txtAbbreviation: { required:true, 
				remote:{  	url: SITE_RELATIVE_URL  + 'ajax',
						type: 'post',
						data: {
							ajax_action: 'verify_language',
							abbreviation: function() { return $( "#txtAbbreviation" ).val(); },
							editId: function() { return frm.EditId(); }
						}
					}
				} 
			};
		var messages = { txtName: 'Specificati numele limbii', txtAbbreviation: { required: 'Specificati abrevierea', remote: 'Aceasta abreviere exista deja' } };
				
		var vW = new ValidatorWrapper();
		vW.InitValidator('mainForm', rules, messages);
	}
}

var ctlLanguageEdit = new LanguageEdit();
ctlLanguageEdit.Init();
