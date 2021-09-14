<?php

namespace models;
use PDO;
use models\Database;
use models\User;
use models\Vocabulary;

class Settings extends MainModel
{
	function addCourse() {
		$this->checkLogin();
		if (!isset($_GET['course'])) $this->badRequest();
		$course = $this->validateCourse($_GET['course']);

		$user = new User();
		$pdo = Database::languages();
		$sql = $pdo->prepare("SELECT `my_courses` FROM `settings` WHERE `user_id` = ? LIMIT 1");
		$sql->execute(array($user->getId()));
		$sql = $sql->fetch(PDO::FETCH_ASSOC);
		$myCourses = array_flip(explode('\\', $sql['my_courses']));

		if (isset($myCourses[$course])) {
			$this->success();
		}

		$myCourses = array_flip($myCourses);
		$myCourses[] = $course;
		$myCourses = implode('\\', $myCourses);

		$sql = $pdo->prepare("UPDATE `settings` SET `my_courses` = ? WHERE `user_id` = ? LIMIT 1");

		if ($sql->execute(array($myCourses, $user->getId())))
			$this->success();
		else
			$this->internalServerError();
	}

	function automaticAudio() {
		$this->checkLogin();
		if (!isset($_GET['auto'])) $this->badRequest();

		$auto = intval($_GET['auto'] == 'true');
		$user = new User();

		$sql = Database::languages();
		$sql = $sql->prepare("UPDATE `settings` SET `auto` = ? WHERE `user_id` = ? LIMIT 1");
		
		if ($sql->execute(array($auto, $user->getId())))
			$this->success();
		else
			$this->internalServerError();
	}

	function changeAudioPlayback() {
		$this->checkLogin();
		if (!isset($_GET['repro'])) $this->badRequest();

		$repro = (int)$_GET['repro'];
		if ($repro < 0 or $repro > 5) $this->badRequest();

		$user = new User();
		$sql = Database::languages();
		$sql = $sql->prepare("UPDATE `settings` SET `repro` = ? WHERE `user_id` = ? LIMIT 1");
		
		if ($sql->execute(array($repro, $user->getId())))
			$this->success();
		else
			$this->internalServerError();
	}

	function changeCourse() {
		$this->checkLogin();
		$course = $this->validateCourse($_GET['course']);
		$user = new User();
		$sql = Database::languages();

		$sql = $sql->prepare("UPDATE `settings` SET `course` = ? WHERE `user_id` = ? LIMIT 1");
		$sql->execute(array($course, $user->getId()));

		$this->success();
	}

	function changeGender() {
		$this->checkLogin();
		if (!isset($_GET['gender'])) $this->badRequest();

		$gender = (string)$_GET['gender'];
		if ($gender != 'M' and $gender != 'F') $this->badRequest();

		$user = new User();
		$sql = Database::languages();
		$sql = $sql->prepare("UPDATE `users` SET `gender` = ? WHERE `id` = ? LIMIT 1");
		
		if ($sql->execute(array($gender, $user->getId())))
			$this->success();
		else
			$this->internalServerError();
	}

	function changeName() {
		$this->checkLogin();
		if (!isset($_GET['name'])) $this->badRequest();

		$name = $this->validateName($_GET['name']);
		$user = new User();

		$sql = Database::languages();
		$sql = $sql->prepare("UPDATE `users` SET `name` = ? WHERE `id` = ? LIMIT 1");

		if ($sql->execute(array($name, $user->getId())))
			$this->success();
		else
			$this->internalServerError();
	}

	function changePrivacy() {
		$this->checkLogin();
		if (!isset($_GET['bool'])) $this->badRequest();

		$private = (int)$_GET['bool'];
		if (($private != 0) and ($private != 1)) $this->badRequest();

		$sql = Database::languages();
		$sql = $sql->prepare("UPDATE `users` SET `private` = ? WHERE `id` = ?");
		$user = new User();
		$sql->execute(array($private, $user->getId()));
	}

	function changeTheme() {
		$this->checkLogin();
		if (!isset($_GET['theme'])) $this->badRequest();

		$theme = intval($_GET['theme'] == 'true');
		$user = new User();
		$sql = Database::languages();
		$sql = $sql->prepare("UPDATE `settings` SET `theme` = ? WHERE `user_id` = ? LIMIT 1");

		if ($sql->execute(array($theme, $user->getId())))
			$this->success();
		else
			$this->internalServerError();
	}

	function getCourses() {
		$this->checkLogin();

		global $lang;
		$courses = ['en'];

		foreach ($courses as $key => $value) {
			$data[$key]['short'] = $value;
			$data[$key]['full'] = $lang->get($value);
			$data[$key]['dicio'] = $this->getDicio($value);
		}

		$this->successWithData($data);
	}

	function getDicio($course) {
		switch ($course) {
			case 'en': return 'https://dictionary.cambridge.org/pt/dicionario/ingles-portugues/{word}';
		}
	}

	function getMyCourses($myCourses) {
		global $lang;
		$myCourses = explode('\\', $myCourses);
		foreach ($myCourses as $key => $value) {
			$course['short'] = $value;
			$course['full'] = $lang->get($value);
			$course['dicio'] = $this->getDicio($value);
			$myCourses[$key] = $course;
		}
		return $myCourses;
	}

	function changeAds() {
		$this->checkLogin();
		if (!$this->isPremium()) $this->unauthorized();
		if (!isset($_GET['bool'])) $this->badRequest();

		$bool = intval($_GET['bool'] == 'true');

		$sql = Database::languages();
		$sql = $sql->prepare("UPDATE `settings` SET `ads` = ? WHERE `user_id` = ? LIMIT 1");
		$user = new User();

		if ($sql->execute(array($bool, $user->getId()))) {
			$this->success();
		}
		else {
			$this->internalServerError();
		}
	}

	function validateName($str) {
		$str = (string)$str;
		$str = strip_tags($str);
		$str = trim($str);
		$str = preg_replace('/( )+/', ' ', $str);
		$length = strlen($str);
		if ($length < 4 or $length > 20) $this->badRequest();
		return $str;
	}
}

?>