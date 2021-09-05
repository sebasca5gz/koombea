<?php
spl_autoload_register(function ($class) {
	$routes = array('system', 'interfaces');
	foreach ($routes as $route) {
		$file = dirname(__FILE__)
			. DIRECTORY_SEPARATOR
			. $route
			. DIRECTORY_SEPARATOR
			. $class .'.php';

		if(is_file($file) && file_exists($file)) {
			require_once $file;
		}
	}
});

spl_autoload_register(function ($class) {
	$modules = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR .'*', GLOB_ONLYDIR);
	foreach($modules as $module) {
		$files = glob($module . DIRECTORY_SEPARATOR . '*.php');
		foreach($files as $file) {
			if(is_file($file) && file_exists($file)) {
				require_once $file;
			}
		}
	}
});
