<?php
	$routes = [];
	$routes['ajax'] = 'ajax/ajax/HandleRequest';
	
	$routes[''] = 'login/login/GetLoginData';
	
	$routes['login[/]'] = 'login/login/GetLoginData';
	$routes['login/{params:.+}'] = 'login/login/GetTestData/$1';
	$routes['logout[/]'] = 'login/login/Logout';
	
	$routes['dashboard[/]'] = 'dashboard/dashboard/GetViewData';
	
	$routes['roles'] = 'roles/roles/GetViewData';
	$routes['roles/edit[/{id:\d+}]'] = 'roles/roles/GetEditData/$1';
	$routes['roles/{data:.+}'] = 'roles/roles/GetViewData/$1';
	
	$routes['users'] = 'users/users/GetViewData';
	$routes['users/edit[/{id:\d+}]'] = 'users/users/GetEditData/$1';
	$routes['users/{data:.+}'] = 'users/users/GetViewData/$1';
	$routes['users_permissions[/{data:.+}]'] = 'users/users_permissions/GetUsersPermissionsData/$1';
	$routes['user_settings[/]'] = 'users/users/GetSettingsData/$1';
		
	$routes['permissions'] = 'permissions/permissions/GetViewData';
	$routes['permissions/edit[/{id:\d+}]'] = 'permissions/permissions/GetEditData/$1';
	$routes['permissions/{data:.+}'] = 'permissions/permissions/GetViewData/$1';
	
	$routes['languages'] = 'languages/languages/GetViewData';
	$routes['languages/edit[/{id:\d+}]'] = 'languages/languages/GetEditData/$1';
	$routes['languages/{data:.+}'] = 'languages/languages/GetViewData/$1';
    //$routes['change_language'] = 'languages/languages/SetSelectedLanguage';
	
	$routes['products'] = 'categories/products/GetViewData';
	$routes['products/edit[/{data:.+}]'] = 'categories/products/GetEditData/$1';
	$routes['products/{data:.+}'] = 'categories/products/GetViewData/$1';
	
	$routes['products_import'] = 'categories/product_import/ImportFeed';
	
	$routes['pictures/upload/{data:.+}'] = 'pictures/pictures/UploadImage/$1';
	$routes['pictures'] = 'pictures/pictures/GetViewData';
	$routes['pictures/crop/{data:.+}'] = 'pictures/picture_crop/GetViewData/$1';
	$routes['pictures/{data:.+}'] = 'pictures/pictures/GetViewData/$1';
	
	$routes['product_image/{data:.+}'] = 'pictures/picture_render/RenderImage/$1';
	$routes['product_thumb/{data:.+}'] = 'pictures/picture_render/RenderThumb/$1';
	
	$routes['categories'] = 'categories/categories/GetViewData';
	$routes['categories/edit[/{id:\d+}]'] = 'categories/categories/GetEditData/$1';
	$routes['categories/{data:.+}'] = 'categories/categories/GetViewData/$1';
	
	//$routes['generate_code'] = 'generate_code/generate_code/GetViewData';

	$routes['app_categories'] = 'app_categories/app_categories/GetViewData';
	$routes['app_categories/edit[/{id:\d+}]'] = 'app_categories/app_categories/GetEditData/$1';
	$routes['app_categories/{data:.+}'] = 'app_categories/app_categories/GetViewData/$1';

	$routes['app_images/upload/{data:.+}'] = 'pictures/pictures/UploadImage/$1';
	$routes['app_images'] = 'pictures/pictures/GetAppImagesViewData';
	$routes['app_images/{data:.+}'] = 'pictures/pictures/GetAppImagesViewData/$1';

	$routes['app_image_edit/{data:.+}'] = 'pictures/app_image_edit/GetEditData/$1';
	$routes['app_image_crop/{data:.+}'] = 'pictures/app_picture_crop/GetViewData/$1';


	$routes['app_image/{data:.+}'] = 'pictures/app_picture_render/RenderImage/$1';
	$routes['app_thumb/{data:.+}'] = 'pictures/app_picture_render/RenderThumb/$1';

	$routes['render_app_image/{data:.+}'] = 'pictures/render_app_image/RenderImage/$1';
	$routes['render_product_image/{data:.+}'] = 'pictures/render_product_image/RenderImage/$1';

    $routes['tmpls/month.html'] = 'events/events/GetMonthTemplate';
    $routes['tmpls/day.html'] = 'events/events/GetDayTemplate';
    $routes['tmpls/month-day.html'] = 'events/events/GetMonthDayTemplate';
    $routes['tmpls/modal.html'] = 'events/events/GetModalTemplate';
    $routes['tmpls/modal-title.html'] = 'events/events/GetModalTitleTemplate';
    $routes['tmpls/week.html'] = 'events/events/GetWeekTemplate';
    $routes['tmpls/week-days.html'] = 'events/events/GetWeekDaysTemplate';
    $routes['tmpls/year.html'] = 'events/events/GetYearTemplate';
    $routes['tmpls/year-month.html'] = 'events/events/GetYearMonthTemplate';
    $routes['tmpls/events-list.html'] = 'events/events/GetEventsListTemplate';
    $routes['events'] = 'events/events/HandleAjaxRequest';

    $routes['events_calendar'] = 'events/events/GetViewData';
    $routes['events_calendar/edit[/{id:\d+}]'] = 'events/events/GetEditData/$1';
    $routes['events_calendar/{data:.+}'] = 'events/events/GetViewData/$1';

    $routes['get_and_save_fb_events'] = 'cron/get_and_save_fb_events/GetFaceBookEvents';
    $routes['get_and_save_fb_events/{data:.+}'] = 'cron/get_and_save_fb_events/GetFaceBookEvents/$1';

    $routes['content'] = 'content/content/GetViewData';
    $routes['content/edit[/{id:\d+}]'] = 'content/content/GetEditData/$1';
    $routes['content/{data:.+}'] = 'content/content/GetViewData/$1';


// {id} must be a number (\d+)
	// $r->addRoute('GET', '/modules/login/{id:\d+}', 'get_user_handler');
	// The /{title} suffix is optional
	// $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
?>