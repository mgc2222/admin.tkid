function RomanianDatepickerWrapper()
{
	this.InitRomanianDatePicker = function(textSelector, minDate, maxDate, defaultDate)
	{
		var currentDate = new Date();
		if (minDate == null)
			minDate = new Date(2008,0,1);
		if (maxDate == null)
			new Date(currentDate.getFullYear() + 4, 11, 31);
		if (defaultDate == null)
			defaultDate = currentDate;
			
		if ($(textSelector).length > 0)
		{
			$.datepicker.setDefaults( $.datepicker.regional[ "ro" ] );
			$(textSelector).datepicker( { showOn: "both",
			changeMonth: true,
			changeYear: true,
			buttonImage: SITE_RELATIVE_URL + "style/admin/ico_calendar.gif",
			buttonImageOnly: true,
			buttonText:'Alege data',
			minDate: minDate,
			maxDate: maxDate,
			defaultDate: defaultDate
			});
		}
	}

	this.InitRomanianDatePickersFromTo = function(textIdFrom, textIdTo, changeToDateOnFromChange, minDate, maxDate, callback)
	{
		if (document.getElementById(textIdFrom) == null) return;
		
		var currentDate = new Date();
		if (minDate == null)
			minDate = new Date(2008,0,1);
		if (maxDate == null)
			new Date(currentDate.getFullYear() + 4, 11, 31);
			
		var dates = $( "#"+textIdFrom+", #"+textIdTo ).datepicker({
			showOn: "both",
			buttonImage: SITE_RELATIVE_URL + "style/admin/ico_calendar.gif",
			buttonImageOnly: true,buttonText:'Alege data',
			defaultDate: "",
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
			minDate: minDate,
			maxDate: maxDate, 
			onSelect: function( selectedDate ) {
				var option = this.id == textIdFrom ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
				
				// set date for DateEnd to be same as selected date
				if (changeToDateOnFromChange && option == 'minDate')
				{
					setTimeout(function() { 
						var dateNext = new Date(date.getTime());
						dateNext.setDate(date.getDate() + 1);
					
						$('#'+textIdTo).datepicker("setDate", dateNext);
						$('#'+textIdTo).datepicker('show'); 
					}, 300);
				}
				
				if (callback != null)
					callback(instance);
			}
		});
	}
};