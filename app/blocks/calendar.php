<?php if (!isset($webpage)) die('Direct access not allowed');  ?>
<section>
    <div class="">
        <div class="page-header" id="calendar-page-header">
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
            <div class="col-md-12" style="padding: 0">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
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

                <h4>Events <small>This list is populated with events dynamically</small></h4>

                <ul id="eventlist" class="nav nav-list"></ul>
            </div>
        </div>
    </div>
</section>
<div class="clearfix"></div>

<div class="modal fade" tabindex="-1" role="dialog"  id="edit-events-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title form-inline"><span style="width:25%"><?php echo $trans['modal.title_label'] ?></span><input type="text" style="width:75%" class="form-control" id="event-title" name="event-title" value=""/></h4>
            </div>
            <div class="modal-body" style="width:100%;display:table;">
               <div class="" style="width:25%;display: table-cell;vertical-align: top;">
                    <span class="form-inline"><?php echo $trans['modal.start_date_label']?></span><input type="text" name="editEventDateStart" class="form-control event-date-start" value="" />
                    <span class="form-inline"><?php echo $trans['modal.end_date_label'] ?></span><input type="text" name="editEventDateEnd"  class="form-control event-date-end"  value="" />
                </div>
                <div style="display:table-cell">
                    <span><?php echo $trans['modal.event_description_label']?></span>
                    <textarea class="form-control event-description" style="width:100%;min-height:400px;">

                    </textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog"  id="new-events-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title form-inline"><span style="width:25%"><?php echo $trans['modal.title_label'] ?></span><input type="text" style="width:75%" class="form-control" id="new-event-title" name="event-title" value=""/></h4>
            </div>
            <div class="modal-body" style="width:100%;display:table;">
                <div class="" style="width:25%;display: table-cell;vertical-align: top;">
                    <span class="form-inline"><?php echo $trans['modal.start_date_label'] ?></span><input type="text" name="newEventDateStart" class="form-control event-date-start" value="" />
                    <span class="form-inline"><?php echo $trans['modal.end_date_label'] ?></span><input type="text" name="newEventDateEnd"  class="form-control event-date-end"  value="" />
                </div>
                <div style="display:table-cell">
                    <span><?php echo $trans['modal.event_description_label']?></span>
                    <textarea class="form-control event-description" style="width:100%;min-height:400px;">

                    </textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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


