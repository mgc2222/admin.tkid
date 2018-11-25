<?php
class Routing
{
	function __construct()
	{
		
	}
	
	function DispatchRoute(&$routes)
	{
		$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r, &$options) {
			foreach ($options['routes'] as $rule=>$handler)
			{
				$r->addRoute(['GET', 'POST'], _SITE_RELATIVE_URL.$rule, $handler); 
			}
		}, array('routes'=>$routes));
		
		// Fetch method and URI from somewhere
		$httpMethod = $_SERVER['REQUEST_METHOD'];
		$uri = $_SERVER['REQUEST_URI'];

		// Strip query string (?foo=bar) and decode URI
		if (false !== $pos = strpos($uri, '?')) {
			$uri = substr($uri, 0, $pos);
		}
		$uri = rawurldecode($uri);

		$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
		
		return $this->HandleRoute($routeInfo);
	}
	
	function HandleRoute(&$routeInfo)
	{
		$ret = new stdClass();
		$ret->status = 'error';
		$ret->message = '';
		$ret->ctl = '';
		$ret->method = '';
		$ret->vars = null;
		
		switch ($routeInfo[0]) 
		{
			case FastRoute\Dispatcher::NOT_FOUND:
				// ... 404 Not Found
					$ret->message = 'route not found';
				break;
			case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
				$allowedMethods = $routeInfo[1];
				// ... 405 Method Not Allowed
					$ret->message = 'method not allowed';
				break;
			case FastRoute\Dispatcher::FOUND:
				$handler = $routeInfo[1];
				$vars = $routeInfo[2];
				$handlerInfo = explode('/', $handler);
				$module = $handlerInfo[0];
				$controller = $handlerInfo[1];
				$method = $handlerInfo[2];
				
				// transform all words to uppercase in order to get controller name
				$fileInfo = explode('_', $controller);
				foreach ($fileInfo as &$fName) 
				{ 
					$fName = ucwords($fName); 
				}
				$className = implode('', $fileInfo);
				$fileName = $controller.'.php';
				
				$classPath = _APPLICATION_FOLDER."modules/{$module}/controllers/{$fileName}";
				if (!file_exists($classPath))
				{
					$ret->message = "Controller file does not exists: {$classPath}";
					break;
				}
				
				if ($controller == 'ajax')
					require_once(_APPLICATION_FOLDER.'core/autoload_ajax.php');
				else
					require_once(_APPLICATION_FOLDER.'core/autoload_admin.php');
				require_once($classPath);
				
				if (!class_exists($className))
				{
					$ret->message = "Controller class does not exists: {$classPath}";
					break;
				}
				
				$ctl = new $className;
				if (!method_exists($ctl, $method)) {
					$ret->message = "Method <b>{$method}</b> does not exists in controller {$classPath}";
					break;
				}
				
				$data = call_user_func_array(array($ctl, $method), $vars);

				$ret->status = 'success';
				$ret->ctl = $ctl;
				$ret->data = $data;
			break;
		}
		
		return $ret;
	}
}
?>