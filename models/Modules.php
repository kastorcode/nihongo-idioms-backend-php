<?php

namespace models;
use PDO;
use models\Database;
use models\Settings;
use models\User;

class Modules extends MainModel
{
	function getModule() {
		$this->checkLogin();
		if (!isset($_GET['id'])) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$id = (int)$_GET['id'];
		$sql = Database::languages();

		$sql = $sql->prepare("SELECT `explanation`, `phrases` FROM `modules` WHERE `id` = ? AND `course` = ? LIMIT 1");
		$sql->execute(array($id, $course));
		$sql = $sql->fetch(PDO::FETCH_ASSOC);
		$sql['phrases'] = $this->getPhrases($sql['phrases']);

		$this->successWithData($sql);
	}

	function getModules() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$sql = Database::languages();

		$sql = $sql->prepare("SELECT `id`, `title` FROM `modules` WHERE `course` = ? ORDER BY `order`");
		$sql->execute(array($course));
		$sql = $sql->fetchAll(PDO::FETCH_ASSOC);

		$this->successWithData($sql);
	}

	function getPhrases($ids) {
		$ids = explode('\\', $ids);
		$phrases = [];
		$pdo = Database::languages();

		foreach ($ids as $value) {
			$sql = $pdo->prepare("SELECT `id`, `phrase`, `translation` FROM `phrases` WHERE `id` = ? LIMIT 1");
			$sql->execute(array($value));
			$sql = $sql->fetch(PDO::FETCH_ASSOC);
			$phrases[] = $sql;
		}
		
		return $phrases;
	}
}

?>