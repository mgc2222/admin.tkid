<?php
// require_once('front/config/defconfig.php');
require_once('app/config/defconfig.php');
if (_DEBUG_MODE) {
	require_once('system/lib/debug/debug.php');
	$debug = new Debug();
	$debug->StartTimer();
}

require_once(_APPLICATION_FOLDER.'vendor/routing/bootstrap.php');

require_once(_APPLICATION_FOLDER.'config/routes.php');
require_once(_APPLICATION_FOLDER.'core/routing.php');

$ctlRouting = new Routing();
$result = $ctlRouting->DispatchRoute($routes);
if ($result->status == 'error')
{
	die($result->message);
}

$ctl = $result->ctl;

$auth = $ctl->GetAuthObject();
$menu = $ctl->GetMenuObject();
$webpage = $ctl->GetWebPageObject();
$trans = $ctl->GetTranslation();
$dataView = $result->data;

include($webpage->PageLayout);
	
if (_DEBUG_MODE) {
	echo '<br/>Rendered in:'.$debug->GetElapsedTime();
}
?>