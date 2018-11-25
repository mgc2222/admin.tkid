<?php
// include config files
$relativePathInclude = dirname(__FILE__).'/../../';

if (_DEBUG_MODE) {
	include_once($relativePathInclude._APPLICATION_FOLDER.'core/enable_errors.php');
}

include_once($relativePathInclude._APPLICATION_FOLDER.'config/dbconfig.php');
include_once($relativePathInclude._APPLICATION_FOLDER.'config/fixconfig.php');

session_start(); // start session

// include database libraries
include_once($relativePathInclude.'system/lib/dbdriver/dbo_abstract.php');
switch (_DB_DRIVER)
{
	case 'mysql': include_once($relativePathInclude.'system/lib/dbdriver/dbo_mysql.php'); break;
	case 'pdo': include_once($relativePathInclude.'system/lib/dbdriver/dbo_pdo.php'); break;
	case 'mysqli': include_once($relativePathInclude.'system/lib/dbdriver/dbo_mysqli.php'); break;
}
include_once($relativePathInclude.'system/lib/dbdriver/dbo.php');

// include common libraries
include_once($relativePathInclude.'system/lib/webpage/webpage.php');

// include auth library
include_once($relativePathInclude._APPLICATION_FOLDER.'lib/auth/auth.php');


// include models which are used in every page
include_once($relativePathInclude.'system/models/abstract_model.php');
include_once($relativePathInclude.'system/controllers/abstract_controller.php');

// instantiate dbo class and connect to db
$dbo = DBO::global_instance();
$dbo->Connect(_DB_DRIVER, _DB_HOST, _DB_USERNAME, _DB_PASSWORD, _DB_DATA_BASE);
?>