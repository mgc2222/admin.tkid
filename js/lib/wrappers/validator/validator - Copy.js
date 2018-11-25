function ValidatorWrapper()
{
	this.InitValidator = function(formId, rules, messages, ajaxCallback, successCallback, holderId, wrapper, errorPlacementCallback)
	{
		if (typeof jQuery.validator !== 'function') return;

		var errorSummary = '<ul></ul>';
		$('#'+holderId).html(errorSummary);
		var errorList = $("#" + holderId + ">ul");
		
		$("#"+formId).validate({
			focusInvalid: false,
			onchange: false,
			rules: rules,
			messages: messages,
			errorLabelContainer: (holderId == null)?holderId:'#'+holderId,
			wrapper: (wrapper == null)?'':wrapper,
			invalidHandler: function(form, validator) {
				if (!validator.numberOfInvalids())
					return;
				$('html, body').animate({
					scrollTop: $(validator.errorList[0].element).offset().top
				}, 1000);
			},
			submitHandler: function(form) {
				$('.edit_table label.error').hide();
				if (ajaxCallback != null)
					return ajaxCallback(form);
				else
					form.submit();
			},
			success: function(label) {
				if (successCallback != null)
					return successCallback(label);
				else
				{				
					label.remove();
					// label.removeClass('error');
				}
			}, 
			errorPlacement: function(error, element)
			{
				if (errorPlacementCallback != null)
					return errorPlacementCallback();
				else 
				{
					error.insertAfter(element);
				}
			}
		});
	}
	
	this.InitValidatorOptions = function(formId, options)
	{
		if (typeof jQuery.validator !== 'function') return;
		if (options.holderId)
		{
			var errorSummary = '<ul></ul>';
			$('#'+options.holderId).html(errorSummary);
			var errorList = $("#" + options.holderId + ">ul");
		}
		
		var defaultOptions = { focusInvalid: false,
			onchange: false,
			invalidHandler: function(form, validator) {
				if (!validator.numberOfInvalids())
					return;
				$('html, body').animate({
					scrollTop: $(validator.errorList[0].element).offset().top
				}, 1000);
			}
		};
		
		$.extend(options, defaultOptions);
		$("#"+formId).validate(options);
	}
}