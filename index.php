<?php
	require_once 'router.php';

	switch ($_SERVER['REQUEST_METHOD']) {
		case 'GET': {
			$url = explode('/', $_GET['url']);
			if (isset($get[$url[0]])) {
				$get[$url[0]]();

			} else {
				die(http_response_code(404));
			}
			break;
		}

		case 'POST': {
			$url = explode('/', $_GET['url']);
			if (isset($post[$url[0]])) {
				$post[$url[0]]();
			}
			else {
				die(http_response_code(404));
			}
			break;
		}

		case 'OPTIONS': {
			die(http_response_code(200));
			break;
		}

		default: {
			die(http_response_code(404));
			break;
		}
	}

	/*switch ($_SERVER['REQUEST_METHOD']) {

		case 'POST':
			$url = isset($_POST['url']) ? explode('/', $_POST['url']) : ['home'];
			if (isset($post[$url[0]])) { $post[$url[0]](); }
			else { $post['home'](); }
		break;

		case 'GET':
			$url = isset($_GET['url']) ? explode("/", $_GET['url']) : ['home'];
			if (isset($get[$url[0]])) { $get[$url[0]](); }
			else { redirect(); }
		break;
	}*/
?>