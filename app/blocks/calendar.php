<?php if (!isset($webpage)) die('Direct access not allowed');  ?>
<section id="section-calendar">
    <div class="">
        <div class="page-header" id="calendar-page-header">
            <div class="grid_buttons pull-left">
                <a role="button" data-toggle="modal" data-target="#eventsModal" class="">
                    <span class="btn btn-success"><i class="fa fa-fw fa-edit"></i> <?php echo $trans['events.new_item']?></span>
                </a>
            </div>
            <div class="pull-right form-inline text-center">
                <div class="btn-group">
                    <button type="button" class="btn <!--btn-primary-->" style="background-color:#d0f4f0" data-calendar-nav="prev"><< <?php echo $trans['calendar.button_previous'] ?></button>
                    <button type="button" class="btn" data-calendar-nav="today"><?php echo $trans['calendar.button_today'] ?></button>
                    <button type="button" class="btn <!--btn-primary-->" style="background-color:#d0f4f0" data-calendar-nav="next"><?php echo $trans['calendar.button_next'] ?> >></button>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn <!--btn-warning-->" style="background-color:#e8fde7" data-calendar-view="year"><?php echo $trans['calendar.button_year'] ?></button>
                    <button type="button" class="btn <!--btn-warning--> active" style="background-color:#e8fde7" data-calendar-view="month"><?php echo $trans['calendar.button_month'] ?></button>
                    <button type="button" class="btn <!--btn-warning-->" style="background-color:#e8fde7" data-calendar-view="week"><?php echo $trans['calendar.button_week'] ?></button>
                    <button type="button" class="btn hidden <!--btn-warning-->" style="background-color:#e8fde7" data-calendar-view="day"><?php echo $trans['calendar.button_day'] ?></button>
                </div>
            </div>
            <h3 class="text-center"></h3>
            <div class="text-center hidden"><small>To see example with events navigate to march 2013</small></div>
        </div>
        <div class="">
            <div class="" style="padding: 0">
                <div id="calendar"></div>
            </div>
            <div class="">
                <div class="row-fluid hidden">
                    <select id="first_day" class="span12">
                        <option value="" selected="selected">First day of week language-dependant</option>
                        <option value="2">First day of week is Sunday</option>
                        <option value="1">First day of week is Monday</option>
                    </select>
                    <select id="language" class="span12">
                        <option value="">Select Language (default: en-US)</option>
                        <option value="bg-BG">Bulgarian</option>
                        <option value="nl-NL">Dutch</option>
                        <option value="fr-FR">French</option>
                        <option value="de-DE">German</option>
                        <option value="el-GR">Greek</option>
                        <option value="hu-HU">Hungarian</option>
                        <option value="id-ID">Bahasa Indonesia</option>
                        <option value="it-IT">Italian</option>
                        <option value="pl-PL">Polish</option>
                        <option value="pt-BR">Portuguese (Brazil)</option>
                        <option value="ro-RO">Romania</option>
                        <option value="es-CO">Spanish (Colombia)</option>
                        <option value="es-MX">Spanish (Mexico)</option>
                        <option value="es-ES">Spanish (Spain)</option>
                        <option value="es-CL">Spanish (Chile)</option>
                        <option value="es-DO">Spanish (República Dominicana)</option>
                        <option value="ru-RU">Russian</option>
                        <option value="sk-SR">Slovak</option>
                        <option value="sv-SE">Swedish</option>
                        <option value="zh-CN">简体中文</option>
                        <option value="zh-TW">繁體中文</option>
                        <option value="ko-KR">한국어</option>
                        <option value="th-TH">Thai (Thailand)</option>
                    </select>
                    <label class="checkbox">
                        <input type="checkbox" value="#edit-events-modal" id="events-in-modal"> Open events in modal window
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" id="format-12-hours"> 12 Hour format
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" id="show_wb" checked> Show week box
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" id="show_wbn" checked> Show week box number
                    </label>
                </div>

                <h4>Evenimente <small>Aceasta lista de mai jos contine toate evenimentele</small></h4>
                <h6>
                    <a href="javascript:;" id="deleteEventsButton" style="display:inline-block;margin-right:20px">
                        <span class="btn btn-danger"
                        <i class="fa fa-fw fa-trash-o"></i> <?php echo $trans['events.delete_selected_items'];?>
                        </span>
                    </a>
                    <span style="font-size:larger">Selecteaza toate evenimentele</span>
                    <span>
                        <input style="display:inline-table;width:20px;height:20px;vertical-align:top"
                               class="form-control form-inline" type="checkbox" id="chkAll" name="chkAll" value="1"
                               onclick="htmlCtl.ToggleCheckboxes('chkAll','multi_checkbox');"
                        />
                    </span>
                </h6>

                <ul id="eventlist" class="nav nav-list"></ul>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="modal fade" tabindex="-1" role="dialog"  id="eventsModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="height:20px">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <h4 class="modal-title form-inline" style="width:100%;display:table;">
                        <div style="width:25%;display: table-cell;">
                            <label><?php echo $trans['modal.event_title_label'] ?></label>
                        </div>
                        <div style="width:100%;display: table-cell;">
                            <input type="text" style="width:100%" class="form-control" id="eventTitle" name="eventTitle" value="" required="required"/>
                        </div>

                    </h4>
                </div>
                <div class="modal-body" style="width:100%;display:table;">
                    <div class="" style="width:25%;display: table-cell;vertical-align: top;">
                        <label class="form-inline"><?php echo $trans['modal.event_start_date_label']?></label>
                        <input type="text" name="eventDateStart" class="form-control event-date-start" id="eventDateStart" value="" />
                        <input type="hidden" name="eventDateStartInMilliseconds" class="event-date-start-in-milliseconds" id="eventDateStartInMilliseconds" value="" required="required" />
                        <input type="hidden" name="eventId" id="eventId" value="" />
                        <label class="form-inline"><?php echo $trans['modal.event_end_date_label'] ?></label>
                        <input type="text" name="eventDateEnd"  class="form-control event-date-end"  value="" />
                        <input type="hidden" name="eventDateEndInMilliseconds" class="event-date-end-in-milliseconds" id="eventDateEndInMilliseconds" value="" />
                        <label for="sel1">Select list (select one):</label>
                        <select class="form-control event-css-classes" id="selectEventsCssClasses" name="selectEventsCssClasses">

                            <option data-event-color-id ="" data-event-color-class="event-warning" value="3">event-warning</option>
                            <option data-event-color-id ="" data-event-color-class="event-success" value="2">event-success</option>
                            <option data-event-color-id ="" data-event-color-class="event-important" value="1">event-important</option>
                            <option data-event-color-id ="" data-event-color-class="event-info" value="4">event-info</option>
                            <option data-event-color-id ="" data-event-color-class="event-special" value="5">event-special</option>
                            <option data-event-color-id ="" data-event-color-class="event-inverse" value="6">event-inverse</option>
                        </select>
                        <label class="form-inline"><?php echo $trans['modal.event_status.is_active']?></label>
                        <input style="display:block;width:20px;height:20px;" type="checkbox" name="eventIsActive" id="eventIsActive" class="form-control form-inline" value="1" />
                        <label><?php echo $trans['modal.event_type'] ?></label>
                        <div id="eventType"></div>
                    </div>
                    <div style="display:table-cell">
                        <label><?php echo $trans['modal.event_description_label']?></label>
                        <textarea name="eventDescription" id="eventDescription" class="form-control event-description" style="width:100%;min-height:400px;">

                        </textarea>
                        <label><?php echo $trans['modal.event_short_description_label']?></label>
                        <textarea name="eventShortDescription" id="eventShortDescription" class="form-control event-short-description" style="width:100%;min-height:100px;">

                        </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" id="deleteEventButton" data-dismiss="modal">Delete Event</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveEventButton" data-dismiss="modal">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</section>
<?php if(false){ ?>
<!--	<script type="text/javascript" src="components/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="components/underscore/underscore-min.js"></script>
	<script type="text/javascript" src="components/bootstrap2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="components/jstimezonedetect/jstz.min.js"></script>
	<script type="text/javascript" src="js/language/bg-BG.js"></script>
	<script type="text/javascript" src="js/language/nl-NL.js"></script>
	<script type="text/javascript" src="js/language/fr-FR.js"></script>
	<script type="text/javascript" src="js/language/de-DE.js"></script>
	<script type="text/javascript" src="js/language/el-GR.js"></script>
	<script type="text/javascript" src="js/language/it-IT.js"></script>
	<script type="text/javascript" src="js/language/hu-HU.js"></script>
	<script type="text/javascript" src="js/language/pl-PL.js"></script>
	<script type="text/javascript" src="js/language/pt-BR.js"></script>
	<script type="text/javascript" src="js/language/ro-RO.js"></script>
	<script type="text/javascript" src="js/language/es-CO.js"></script>
	<script type="text/javascript" src="js/language/es-MX.js"></script>
	<script type="text/javascript" src="js/language/es-ES.js"></script>
	<script type="text/javascript" src="js/language/es-CL.js"></script>
	<script type="text/javascript" src="js/language/es-DO.js"></script>
	<script type="text/javascript" src="js/language/ru-RU.js"></script>
	<script type="text/javascript" src="js/language/sk-SR.js"></script>
	<script type="text/javascript" src="js/language/sv-SE.js"></script>
	<script type="text/javascript" src="js/language/zh-CN.js"></script>
	<script type="text/javascript" src="js/language/cs-CZ.js"></script>
	<script type="text/javascript" src="js/language/ko-KR.js"></script>
	<script type="text/javascript" src="js/language/zh-TW.js"></script>
	<script type="text/javascript" src="js/language/id-ID.js"></script>
	<script type="text/javascript" src="js/language/th-TH.js"></script>
	<script type="text/javascript" src="js/calendar.js"></script>
	<script type="text/javascript" src="js/app.js"></script>-->
<?php } ?>


