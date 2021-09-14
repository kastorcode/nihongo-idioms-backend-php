<?php

namespace models;
use models\User;

class MainModel
{
	function badRequest() {
		http_response_code(400);
		die();
	}

	function checkLogin() {
		if (!isset($_SESSION['id'])) {
			$user = new User();
			$user->refreshSession();

			if (!isset($_SESSION['id'])) {
				http_response_code(401);
				die();
			}
		}
	}

	function internalServerError() {
		http_response_code(500);
		die();
	}

	function isPremium() {
		return $_SESSION['premium'] > 0;
	}

	function redirect($path = PATH) {
		echo "<script>location.href='$path'</script>";
		$this->success();
	}

	function success() {
		http_response_code(200);
		die();
	}

	function successWithData($data) {
		http_response_code(200);
		die(json_encode($data));
	}

	function unauthorized() {
		http_response_code(401);
		die();
	}

	function validateCourse($course) {
		$course = (string)$course;

		switch ($course) {
			case 'en':
			return $course;

			default: $this->badRequest();
		}
	}
}

?>