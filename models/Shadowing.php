<?php

namespace models;
use PDO;
use models\Database;
use models\User;
use models\Vocabulary;

class Shadowing extends MainModel
{
	function getShadowing() {
		$this->checkLogin();
		if (!isset($_GET['id'])) $this->badRequest();
		$id = (int)$_GET['id'];

		$sql = Database::languages();
		$sql = $sql->prepare("SELECT `text` FROM `shadowing` WHERE `id` = ? LIMIT 1");
		$sql->execute(array($id));
		
		$this->successWithData($sql->fetch(PDO::FETCH_ASSOC));
	}

	function getTexts() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$sql = Database::languages();

		$sql = $sql->prepare("SELECT `id`, `level`, `title` FROM `shadowing` WHERE `course` = ? ORDER BY `order`");
		$sql->execute(array($course));
		$sql = $sql->fetchAll(PDO::FETCH_ASSOC);

		$this->successWithData($sql);
	}
}

?>