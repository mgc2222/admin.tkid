(function($) {

	"use strict";

	var options = {
		events_source: 'events',
		view: 'week',
		tmpl_path: 'tmpls/',
		tmpl_cache: false,
		day: 'now',
		language:language,
		onAfterEventsLoad: function(events) {
			if(!events) {
				return;
			}
			var list = $('#eventlist');
			list.html('');

			$.each(events, function(key, val) {
				//debugger;
				$(document.createElement('li'))
					.html('<a ' +
						'data-toggle="modal" ' +
						'data-target="#new-events-modal" ' +
						'data-description="'+val.description+'" ' +
						'data-short-description="'+((val.short_description) ? val.short_description : "")+'" ' +
						'data-event-css-class="'+((val.class) ? val.class : "")+'" ' +
						'title="'+val.title+'" ' +
						'data-date-start="'+val.start+'" ' +
						'data-date-end="'+val.end+'" ' +
						'data-event-id="'+val.id+'" ' +
						'class="calendar-event" ' +
						//'href="' + val.url +
						'>' +
							val.title +
						'</a>')
					.appendTo(list);
			});
		},
		onAfterViewLoad: function(view) {
			$('.page-header h3').text(this.getTitle());
			$('.btn-group button').removeClass('active');
			$('button[data-calendar-view="' + view + '"]').addClass('active');
		},
		classes: {
			months: {
				general: 'label'
			}
		},
		modal:"#edit-events-modal",
		modal_type:"template",
		modal_title: function(obj){
			return (obj.hasOwnProperty('title')) ? obj.title : 'Event';
		}
	};

	var calendar = $('#calendar').calendar(options);

	$('.btn-group button[data-calendar-nav]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data('calendar-nav'));
		});
	});

	$('.btn-group button[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.view($this.data('calendar-view'));
		});
	});

	$('#first_day').change(function(){
		var value = $(this).val();
		value = value.length ? parseInt(value) : null;
		calendar.setOptions({first_day: value});
		calendar.view();
	});

	$('#language').change(function(){
		calendar.setLanguage($(this).val());
		calendar.view();
	});

	$('#events-in-modal').change(function(){
		var val = $(this).is(':checked') ? $(this).val() : null;
		calendar.setOptions({modal: val});
	});
	$('#format-12-hours').change(function(){
		var val = $(this).is(':checked') ? true : false;
		calendar.setOptions({format12: val});
		calendar.view();
	});
	$('#show_wbn').change(function(){
		var val = $(this).is(':checked') ? true : false;
		calendar.setOptions({display_week_numbers: val});
		calendar.view();
	});
	$('#show_wb').change(function(){
		var val = $(this).is(':checked') ? true : false;
		calendar.setOptions({weekbox: val});
		calendar.view();
	});
	$('#edit-events-modal .modal-header, #edit-events-modal .modal-footer').click(function(e){
		//e.preventDefault();
		//e.stopPropagation();
	});
}(jQuery));