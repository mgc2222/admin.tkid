<!-- javascript -->
<div style="display:none" id="footerJS">
<?php if ($webpage->ScriptsFooter != null) { ?>
<script type="text/javascript"><?php echo $webpage->JsPageContent?></script>
<script type="text/javascript" src="<?php echo _SITE_URL?>js/lib/loader/jsload.js"></script>
<?php } ?>
<?php if ($webpage->JsMessage != null) echo HtmlControls::JsAlert($webpage->JsMessage); ?>
</div>