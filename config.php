<?php
	use models\Lang;

	ini_set('session.use_cookies', 0);
	ini_set('session.use_trans_sid', 1);
	@session_id($_GET['session']);
	session_start();

	date_default_timezone_set('America/Sao_Paulo');

	spl_autoload_register(function($class) {
		$class = str_replace('\\', '/', $class).'.php';
		if (file_exists($class)) {
			require_once $class;
		}
	});

	define('PATH', 'https://localhost/nihongo-idioms-backend-php');
	define('PATH_VIEWS', PATH.'/views');
	define('PATH_CSS', PATH_VIEWS.'/css');
	define('PATH_IMAGES', PATH_VIEWS.'/images');
	define('PATH_JS', PATH_VIEWS.'/js');

	define('DIR', realpath(__DIR__));
	define('DIR_JS', DIR.'/views/js');

	$lang = new Lang();
?>
