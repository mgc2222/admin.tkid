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
        $('#new-events-modal').on('show.bs.modal', function (e) {
            initStartDateTimePickers($(this), null);
            initEndDateTimePickers($(this), null);

            /*$(this)
                .find("input,textarea,select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();*/
        });
        $('#edit-events-modal').on('show.bs.modal', function (e) {
            debugger;
            ($(e.relatedTarget)[0].attr('data-title')) ? $(this).find("#event-title").attr('value', $(e.relatedTarget)[0].attr('data-title')) : '';
            ($(e.relatedTarget)[0].attr('data-date-start')) ?  initStartDateTimePickers($(this), $(e.relatedTarget[0]).attr('data-date-start')) : '';
            ($(e.relatedTarget)[0].attr('data-date-end')) ?  initStartDateTimePickers($(this), $(e.relatedTarget[0]).attr('data-date-end')) : '';
            ($(e.relatedTarget)[0].attr('data-date-description')) ?  addModalEventDescription($(this), $(e.relatedTarget[0]).attr('data-description')) : '';
        });
	}

	this.initEditEventModal = function(event, modal){
        initStartDateTimePickers(modal, event.start);
        initEndDateTimePickers(modal, event.end);
        addModalEventDescription(modal, event.description)
    };

    function addModalEventDescription(modal, description){
        $(modal).find('.event-description').val((description) ? description : '');
    }

    function initStartDateTimePickers(modal, startDateInMilliseconds)
    {
        $(modal).find('.event-date-start').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            singleDatePicker: true,
            startDate: (startDateInMilliseconds) ? new Date(parseInt(startDateInMilliseconds)) : new Date(),
            locale: {
                format: 'DD/MM/YY HH:mm'
            }
        });

    }

    function initEndDateTimePickers(modal, startDateInMilliseconds)
    {
        $(modal).find('.event-date-end').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            singleDatePicker: true,
            startDate:  (startDateInMilliseconds) ? new Date(parseInt(startDateInMilliseconds)) : new Date(),
            locale: {
                format: 'DD/MM/YY HH:mm'
            }
        });
    }

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