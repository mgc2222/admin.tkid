function I18NextTranslate(options)
{
	var _options = {  lng: 'ro', fallbackLng: 'ro', resGetPath: SITE_RELATIVE_URL + 'application/langs/__lng__/__ns__.json', lngWhitelist: ['ro', 'en', 'fr', 'de'], selector: '.page_content', callback: i18nCallback };
	
	options = $.extend({}, _options, options)
	
	i18n.init(options).done(function () {
		$(options.selector).i18n();
		if (options.callback != null)
			options.callback();
	});
}