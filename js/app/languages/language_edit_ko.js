function LanguagesVm(data) {
    var _this = this;
	this.PageHeadTitle = ko.observable($('.page_title').html());
	this.isEdit = ko.observable();
	
	this.id = 0;
	this.name = ko.observable();
	this.abbreviation = ko.observable();
	this.defaultLanguage = ko.observable();
	this.isTranslated = ko.observable();
	
	loadData(data);
	initValidator();
	
	this.save = function()
	{
		var data = { id: this.id, name: this.name(), abbreviation: this.abbreviation(), default_language: this.defaultLanguage()?1:0, is_translated: this.isTranslated()?1:0 }
		saveDataModel(data, SITE_RELATIVE_URL + 'languages');
	}
	
	function loadData(data)
	{
		if (data != null)
		{
			_this.id = data.id;
			_this.name(data.name);
			_this.abbreviation(data.abbreviation);
			_this.defaultLanguage(data.default_language);
			_this.isTranslated(data.is_translated);
			
			_this.isEdit(this.id != 0);
		}
	}
	
	function saveDataModel(data, url)
	{
		data.ajaxAction = 'save';
		
		var promise = $.ajax({
			type: "POST",
			url: url,
			data: JSON.stringify(data),
			contentType: "application/json; charset=utf-8",
			dataType: "json"
		}).done(function(response) {
			showResponse(response);
			_this.id = response.id;
			if (response.pageTitle)
				_this.PageHeadTitle(response.pageTitle);
		})
		.fail(function(response) {
			showResponse(response);
		});
	}
	
	function showResponse(response)
	{
		toastr[response.status](response.message);
	}
	
	function initValidator()
	{
		var rules = { 
			txtName: 'required',
			txtAbbreviation: { required:true, 
				remote:{  	url: SITE_RELATIVE_URL + 'ajax',
						type: 'post',
						data: {
							ajax_action: 'verify_language',
							abbreviation: function() { return _this.abbreviation(); },
							editId: function() { return  _this.id; }
						}
					}
				} 
			};
		var messages = { txtName: 'Specificati numele limbii', txtAbbreviation: { required: 'Specificati abrevierea', remote: 'Aceasta abreviere exista deja' } };
				
		var vW = new ValidatorWrapper();
		vW.InitValidator('mainForm', rules, messages);
	}
}

function i18nCallback()
{
	var dbData = JSON.parse(dataJson);
	ko.applyBindings(new LanguagesVm(dbData));
}

I18NextTranslate();