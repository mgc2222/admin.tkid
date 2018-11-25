(function($){
	/**
	 *	in order to work, is required frm variable to be declared outside this class: frm = new FormClass();
	 * Instance $('.'+class).valid("form_name", method, action name, action params, summary block Id);
	 * 	method :'alert' : display method error in alert;
	 * 			'name/rule' : error message is displayed into a span element. 
	 *						The id is name and role Ex. : <span id="txtUsername_required">Completati Nume Utilizator</span>
	 * 	acName: action that will be set in the hidden field sys_Action
	 * 	acParams: params that will be set in the hidden field sys_Params
	 * 	summaryBlockId: the id of the error block wrapper
	 
	 *	usage: $('.validate').valid('mainForm', 'name/rule', 'Save', '','divErrors');
	 *  IMPORTANT: validation methods must be the first class of the element
	 */
	
	var ajaxReturned 	= Array();
	var verifyAjaxCount = 0;
	var verifyAjax 		= false;
	var maxTimeout 		= 10000;
	var currentTime 	= 0;
	var formID 			= '';
	var displayMethod 	= 'alert';
	var actionName		= '';
	var actionParams		= '';
	var blockId		= '';
	
	$.fn.valid = function(formElement, method, acName, acParams, summaryBlockId){
		var className;		
		var Valid = true;
		formID = formElement;
		displayMethod = method;
		actionName = acName;
		actionParams = acParams;
		blockId = summaryBlockId; // for summary block, give the id of the holder
		
		this.each(function() {
			// Get necesary information about current element
			className 		= $(this).attr("class").split(" ");
			var messages = "";
			if ($(this).attr("title") != undefined)
				messages 	= $(this).attr("title").split('|');
			var rules 		= className[0].split("|");
			//var crtElement  = $('[name='+$(this).attr("name")+']')

			for(var i=0; i<rules.length; i++)
			{
				var message = ""
				if(messages[i]!=null){
					message = messages[i];
				}
				
				if (isValid($(this), rules[i], message)==false){
					Valid = false;
					break;
				}
			}
			
		});
		if(Valid==false)
		{
			if (blockId != '')
				$('#'+blockId).show();
			return false;
		}
		
		var event = false;
		if(className!=undefined)
		{
			if(className[1]!=undefined)
			{
				event = className[1]; 
			} else {
				event = className[0];
			}
		}
		$('#event').remove();
		$('form[name='+formID+']').append('<input type="hidden" id="event" name="event" value="'+event+'" />');
		//alert(verifyAjax);
		if (verifyAjax)
		{
			currentTime = 0;
			timeInterval = setTimeout(mainLoop, 100)
		}
		else
			submitForm();
	}// ~valid
	
	
	function displayMessage(message, id)
	{
		
		switch(displayMethod)
		{
			case 'alert':
				alert(message);
			break;
			case 'name/rule':
				$('#'+id).css('display', 'block');
			break;
		}
	}
	
	function hideMessage(id)
	{
		switch(displayMethod)
		{
			case 'name/rule':
				$('#'+id).css('display', 'none');
			break;
		}
	}
	
	
	function mainLoop()
	{
		
		var doSubmit = true;
		var isValidData = true;
		for (var i=1; i<= verifyAjaxCount; i++)
		{
			if (ajaxReturned[i] == 0)
				doSubmit = false;
			if (ajaxReturned[i] == -1)
				isValidData = false;
		}
		
		if (doSubmit)
		{
			clearTimeout(timeInterval);
			if (isValidData)
				submitForm();
			else
			{
				if (blockId != '')
					$('#'+blockId).show();
			}
		}
		
		if (currentTime > maxTimeout )
			clearTimeout(timeInterval);
		
		currentTime+=100;
	}
	
	function submitForm()
	{
		if (blockId != '')
			$('#'+blockId).hide();
		if (actionName != '')
			frm.FormSubmitAction(actionName, actionParams, formID); // frm = variable declared in admin_init
		else
			document.forms[formID].submit();
	}
	/**
	 * Private method
	 */
	function isValid(element, rule, message)
	{
		
		var inputName 	= element.prev().html();
		var RuleType = rule.match(/^\w+/); 
		var Argument = rule.match(/\[(.*)\]/);
		if(Argument !=null)
			Argument = Argument[1].toString();
		else Argument = "";
		if(RuleType !=null)
			RuleType = RuleType[0].toString();
		else RuleType = "";
		
		switch(RuleType){
			case 'required':
				if(element.attr('type')=='checkbox')
				{
					if(element.attr('checked')==false)
					{
						if(message=='')
							displayMessage('The field '+inputName+' is required', element.attr('id')+'required');
						displayMessage(message, element.attr('id')+'_required');
						return false;
					}
				} else {
					if (element.val() == null || element.val().length==0)
					{
						if(message=='')
							displayMessage('The field '+inputName+' is required', element.attr('id')+'required');
						displayMessage(message, element.attr('id')+'_required');
						return false;
					}
				}
				hideMessage(element.attr('id')+'_required');
			break;
			case 'numeric':
				if(element.val().length!=0){
					var str = element.val().toString();
					if(str.match(/^(-[0-9]+\.[0-9]+|[0-9]+\.[0-9]+|[0-9]+)$/)==null){
						if(message=='')
							message = 'The field '+inputName+' is not numeric';
						displayMessage(message, element.attr('id')+'_numeric');
						return false;
					}
				}
				hideMessage(element.attr('id')+'_numeric');
			break;
			case 'naturalNumber':
				if(element.val().length!=0){
					var str = element.val().toString();
					if(str.match(/^\d+$/)==null){
						if(message=='')
							message = 'The field '+inputName+' is not natural number';
						displayMessage(message, element.attr('name')+'_naturalNumber');
						return false;
					}
				}
				hideMessage(element.attr('name')+'_naturalNumber');
			break;
			case 'integer':
				if(element.val().length!=0){
					var str = element.val().toString();
					if(str.match(/^-?\d+$/)==null){
						if(message=='')
							message = 'The field '+inputName+' is not integer';
						displayMessage(message, element.attr('name')+'_integer');
						return false;
					}
				}
				hideMessage(element.attr('name')+'_integer');
			break;
			case 'alphaNumPlus':
				if(element.val().length!=0){
					var str = element.val().toString();
					if(str.match(/^([A-Za-z0-9_]+)$/)==null){
						if(message=='')
							message = 'The field '+inputName+' must be AlphaNumeric';
						displayMessage(message, element.attr('name')+'_alphaNumPlus');
						return false;
					}
				}
				hideMessage(element.attr('name')+'_alphaNumPlus');
			break;
			case 'email':
				if(element.val().length!=0){
					//if(element.val().search(/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/)==-1){
					if(element.val().search(/^[-_.a-z0-9]+@(([-_a-z0-9]+\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i) == -1) {
						if(message=='')
							message = 'The field is '+inputName+' not a valid email address';
						displayMessage(message, element.attr('name')+'_email');
						return false;
					}
				}
				hideMessage(element.attr('name')+'_email');
			break;
			case 'hyperlink':
				if(element.val().length!=0){
					if(element.val().search(/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/)==-1){
						if(message=='')
							message = 'The field is '+inputName+' not a valid hyperlink';
						displayMessage(message, element.attr('name')+'_hyperlink');
						return false;
					}
				}
				hideMessage(element.attr('name')+'_hyperlink');
			break;
			case 'match':
				if($('input[name='+Argument+']').val()!=element.val()){
						if(message=='')
							message = 'The field is '+inputName+' and '+$('#'+Argument).prev().html()+' doesn\'t match';
						displayMessage(message, element.attr('name')+'_match');
					return false;
				}
			break;
			case 'ajax':
				verifyAjaxCount++;
				ajaxReturned[verifyAjaxCount] = 0;
				verifyAjax = true;
				$.get(Argument, null, function (result){
					ajaxReturned[verifyAjaxCount] = 1;
					if(result.match(/result=\[\w+\]/).toString()!="result=[true]")
					{
						ajaxReturned[verifyAjaxCount] = -1;
						//alert(result.match(/result=\[\w+\]/).toString());
						if(message=='')
							message ='The field '+inputName+' is not valid';
						displayMessage(message, element.attr('name')+'_ajax');
					} else {
						hideMessage(element.attr('name')+'_ajax');
						ajaxReturned[verifyAjaxCount] = 1;
					}
				});
			break;
			case 'maximlen':
					if(element.val().length > Argument){
						if(message!="")
							message = 'The field '+inputName+' have to be no longer then '+Argument+' chars!';
						displayMessage(message, element.attr('name')+'_maximlen');
						return false;
					}
					hideMessage(element.attr('name')+'_maximlen');
			break;
			case 'minimlen':
				if(parseInt(element.val().length, 10) < parseInt(Argument,10)){
					if(message=="")
						message = 'The field '+inputName+' have to be at least '+Argument+' chars!';
					displayMessage(message, element.attr('name')+'_minimlen');
					return false;
				}
				hideMessage(element.attr('name')+'_minimlen');
			break;
			case 'host':
				if(element.val().match(/\w+\.\w+\.\w{2,3}/)!=-1 &&  element.val().match(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/)!=-1 ){
					if(message=="")
						message = 'The field '+inputName+' is not a valid host address!';
					displayMessage(message, element.attr('name')+'_host');
					return false;
				}
				hideMessage(element.attr('name')+'_host');
			break;
		}//~switch
		return true;
	}// ~isValid
})(jQuery);