function Events()
{
	this.Init = function()
	{
		//initSortable();
		initControls();

	};
	
	function initSortable()
	{
		var objSortableWrapper = new SortableWrapper();
		var categoryId = objSortableWrapper.GetParameterByName('id');
		var queryParams = { id: categoryId }
		var options = { tablename:'sortable', urlServer: SITE_RELATIVE_URL + 'events', colorRowOdd:'eeeeee', colorRowEven:'FEFEFE', queryParams:queryParams};
		objSortableWrapper.SetOptions(options);
		objSortableWrapper.Init();
		
		$('.grid_view tr').addClass('allow_drag'); // class to show hand cursor
	}
	
	function initControls()
	{
		$('#chkAll').click(function() {
			htmlCtl.ToggleCheckboxes('chkAll','multi_checkbox');
		});

        $('#eventsModal').on('show.bs.modal', function (e) {
            var eventTitle = $("#eventTitle");
            var eventId = $("#eventId");
            var eventIsActive = $("#eventIsActive");
            var eventStatus = e.relatedTarget.getAttribute('data-event-status');
            var eventType = e.relatedTarget.getAttribute('data-event-type');
            eventTitle.val(e.relatedTarget.getAttribute('title'));
            eventId.val(e.relatedTarget.getAttribute('data-event-id'));
            (eventStatus) ?  eventIsActive.val(eventStatus) :   eventIsActive.val(1);
            (eventIsActive.val() > 0 ) ? eventIsActive.prop('checked', true) : eventIsActive.prop('checked', false);
            $('#eventType').html((eventId.val())? eventType : 'Local');
            initStartDateTimePickers($(this), e.relatedTarget.getAttribute('data-date-start'));
            initEndDateTimePickers($(this), e.relatedTarget.getAttribute('data-date-end'));
            selectOrResetEventCssClass($(this), e.relatedTarget.getAttribute('data-event-css-class'));
            initSelect2Event($(this));
            addModalEventDescription($(this), e.relatedTarget.getAttribute('data-description'));
            addModalEventShortDescription($(this), e.relatedTarget.getAttribute('data-short-description'));
            (eventId.val()) ? $('#deleteEventButton').show() : $('#deleteEventButton').hide();
        });
        $('.event-date-start').on('change', function (e) {
            var value = (languageAbbIso==='ro_RO')
                ? new Date(moment($(this).val(), 'DD/M/YYYY HH:mm').format('YYYY/M/DD HH:mm')).getTime() :
                new Date(moment($(this).val(), 'YYYY/M/DD HH:mm').format('YYYY/M/DD HH:mm')).getTime();
            $(this).next('.event-date-start-in-milliseconds').val(value);
        });
        $('.event-date-end').on('change', function (e) {
            var value = (languageAbbIso==='ro_RO')
                ? new Date(moment($(this).val(), 'DD/M/YYYY HH:mm').format('YYYY/M/DD HH:mm')).getTime() :
                new Date(moment($(this).val(), 'YYYY/M/DD HH:mm').format('YYYY/M/DD HH:mm')).getTime();
            $(this).next('.event-date-end-in-milliseconds').val(value);
        });
	}
    this.checkForm = function()
    {
        if($('#eventTitle').val() == "") {
            alert("Titlul evenimentului este obligatoriu!");
            $('#eventTitle').focus();
            return false;
        }
        if($('#eventDateStartInMilliseconds').val() == "") {
            alert("Data si ora de inceput a evenimentului este obligatorie!");
            $('#eventDateStartInMilliseconds').focus();
            return false;
        }

        //alert("Success!  The form has been completed, validated and is ready to be submitted...");
        return true;
    };

    function addModalEventDescription(modal, description){
        $(modal).find('.event-description').val((description) ? description : '');
    }

    function addModalEventShortDescription(modal, shortDescription){
        $(modal).find('.event-short-description').val((shortDescription) ? shortDescription : '');
    }

    function addSelect2SelectedColorEvent(modal, selectedOptionEventColorClass){
        //debugger;
        var select2Selection = modal.find('.select2-selection');
        var eventColorClass = select2Selection.attr('data-event-color-class');
        select2Selection.removeClass('day-highlight dh-' + eventColorClass);
        if(selectedOptionEventColorClass){
            select2Selection.attr('data-event-color-class',selectedOptionEventColorClass).addClass('day-highlight dh-' + selectedOptionEventColorClass);
        }
    }

    function selectOrResetEventCssClass(modal, eventCssClass){
        //debugger;
        var value = '';
        if(eventCssClass){
            $(modal).find('.event-css-classes option').each(function () {
                $(this).prop('selected', false);
                if (this.getAttribute('data-event-color-class') === eventCssClass) {
                    $(this).prop('selected', 'selected');
                    value = this.value;
                }
            });
        }
        else{
            $(modal).find('.event-css-classes option:selected').prop('selected', false);
        }
        $(modal).find('.event-css-classes').val(value);
        //debugger;
    }

    function initStartDateTimePickers(modal, startDateInMilliseconds)
    {
        $(modal).find('.event-date-start').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            singleDatePicker: true,
            startDate: (startDateInMilliseconds) ? new Date(parseInt(startDateInMilliseconds)) : moment().set({hour:18, minute:0}),
            locale: {
                format: (languageAbbIso==='ro_RO') ? 'DD/M/YYYY HH:mm' : 'YYYY/M/DD HH:mm'
            }
        });

    }

    function initEndDateTimePickers(modal, endDateInMilliseconds)
    {
        $(modal).find('.event-date-end').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            singleDatePicker: true,
            startDate:  (endDateInMilliseconds) ? new Date(parseInt(endDateInMilliseconds)) : moment().set({hour:20, minute:0}),
            locale: {
                format: (languageAbbIso==='ro_RO') ? 'DD/M/YYYY HH:mm' : 'YYYY/M/DD HH:mm'
            }
        });
    }

    function initSelect2Event(modal){
        $('#selectEventsCssClasses').select2({
            templateResult: function (data, container) {
                if (data.element) {
                    $(container).addClass('day-highlight dh-' +$(data.element).attr("data-event-color-class"));
                    $(container).attr("data-event-color-class", $(data.element).attr("data-event-color-class"));
                }
                return data.text;
            },
            placeholder: "Select a color",
            templateSelection: function(data, container){
                if(data.selected){
                    addSelect2SelectedColorEvent(modal, data.element.getAttribute('data-event-color-class'));
                }
                return data.text;
            }
        });
    }

    function getCheckboxesValues(classname){
        //debugger;
        var values = new Array();
        $('.'+classname+":checked").each(function() {
            values.push($(this).val());
        });
        //debugger;
        return values;
    }

    this.saveEvent = function(objCalendar)
    {
        frm.PostAjaxJson('events_calendar',
            {
                ajaxAction: 'SaveEvent',
                formValues:
                    {
                        id: $('#eventId').val(),
                        title: $('#eventTitle').val(),
                        event_start_unix_milliseconds: $('#eventDateStartInMilliseconds').val(),
                        event_end_unix_milliseconds: $('#eventDateEndInMilliseconds').val(),
                        event_css_class_id: $('#selectEventsCssClasses').val(),
                        status: ($('#eventIsActive').is(':checked')) ? $('#eventIsActive').val() : 0,
                        description: $('#eventDescription').val(),
                        short_description: $('#eventShortDescription').val(),
                    }

            },
            function(response) {
                //debugger;
                objCalendar.view();
                toastr[response.status](response.message);
            },
            function(response) {
                toastr[response.status](response.message);
            }
        );
    };

    this.deleteEvents = function(objCalendar, className)
    {
        if (className == null) className = 'multi_checkbox';

        if (!frm.verifySelectedChecboxesCount(className)) return;
        if (confirm('Confirmati stergerea evenimentelor selectate ?')) {
            //debugger;
            frm.PostAjaxJson('events_calendar',
                {
                    ajaxAction: 'DeleteEvents',
                    formValues: {
                        id: getCheckboxesValues(className)
                    }
                },
                function (response) {
                    //debugger;
                    objCalendar.view();
                    toastr[response.status](response.message);
                },
                function (response) {
                    toastr[response.status](response.message);
                }
            );
        }
    };
    this.deleteEvent = function(objCalendar)
    {
        if (confirm('Confirmati stergerea evenimentului?')){
            frm.PostAjaxJson('events_calendar',
                {
                    ajaxAction: 'DeleteEvent',
                    formValues:
                        {
                            id: $('#eventId').val()
                        }

                },
                function(response) {
                    //debugger;
                    objCalendar.view();
                    toastr[response.status](response.message);
                },
                function(response) {
                    toastr[response.status](response.message);
                }
            );
        }

    };



    function initPopovers()
	{
        var popOverSettings = {
            placement: 'top',
            container: 'body',
            //html: true,
            //trigger: 'manual',
            selector: '[data-toggle="popover"]', //Specify the selector here
			/*content: function () {
			 return $('#popover-content').html();
			 }*/
        };

        $('#calendar').popover(popOverSettings);
		$('body').on('click', function (e) {
            var calendarCell = $('.calendar-event');
            var popovers = $('.popover');
            var tooltips = $('.tooltip');
            if (calendarCell.has(e.target).length === 0 && !calendarCell.is(e.target) && popovers.length) {
                $('[data-toggle="popover"]').popover('destroy');
                popovers.remove();
            }
            if(tooltips.length){
                tooltips.remove();
            }
        });
	}
}
var objEvents = new Events();
objEvents.Init();