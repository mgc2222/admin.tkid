<?php if (!isset($webpage)) die('Direct access not allowed');  ?>
<section data-id="dkdnqry" class="page type-page status-publish hentry mhmm">
    <header class="entry-header">
        <h2 class="elementor-heading-title elementor-size-large entry-title page-section" id="section-calendar">
            <?php echo $trans['calendar.section_title'];?>
        </h2>
    </header>
    <div class="elementor-widget">
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
                <div class="row-fluid">
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
                        <input type="checkbox" value="#events-modal" id="events-in-modal"> Open events in modal window
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

                <h4>Events</h4>
                <small>This list is populated with events dynamically</small>
                <ul id="eventlist" class="nav nav-list"></ul>
            </div>
        </div>
    </div>
</section>
<div class="clearfix"></div>
<div class="modal hide fade" id="events-modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Event</h3>
    </div>
    <div class="modal-body" style="height: 400px">
    </div>
    <div class="modal-footer">
        <a href="#" data-dismiss="modal" class="btn">Close</a>
    </div>
</div>
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


