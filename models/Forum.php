<?php

namespace models;
use PDO;
use models\Database;
use models\User;

class Forum extends MainModel
{
	function addQuestion() {
		$this->checkLogin();
		if (!isset($_GET['question']) or !isset($_GET['content']))
			$this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$question = $this->validateQuestion($_GET['question']);
		$content = $this->validateQuestionContent($_GET['content']);
		$user = new User();
		$sql = Database::languages();

		$sql = $sql->prepare("INSERT INTO `forum_questions` (`id`, `course`, `user_id`, `date`, `replies`, `question`, `content`) VALUES (?,?,?,?,?,?,?)");

		if ($sql->execute(array(null, $course, $user->getId(), date('Y-m-d'), 0, $question, $content)))
			$this->success();
		else
			$this->internalServerError();
	}

	function addReply() {
		$this->checkLogin();
		if (!isset($_GET['id']) or !isset($_GET['reply'])) $this->badRequest();

		$reply = $this->validateReply($_GET['reply']);
		$questionId = (int)$_GET['id'];
		$user = new User();
		$userId = $user->getId();
		$date = date('Y-m-d');

		$sql = Database::languages();
		$sql = $sql->prepare("INSERT INTO `forum_replies` (`id`, `question_id`, `user_id`, `date`, `reply`) VALUES (?,?,?,?,?)");

		try {
			$sql->execute(array(null, $questionId, $userId, $date, $reply));
		}
		catch (\PDOException $e) {
			$this->internalServerError();
		}
		
		$this->success();
	}

	function deleteQuestion() {
		$this->checkLogin();

		$id = (int)$_GET['id'];
		$user = new User();
		$sql = Database::languages();

		$sql = $sql->prepare("DELETE FROM forum_questions WHERE id = ? AND user_id = ? LIMIT 1");
		$sql->execute(array($id, $user->getId()));

		$this->success();
	}

	function deleteReply() {
		$this->checkLogin();

		$id = (int)$_GET['id'];
		$user = new User();
		$sql = Database::languages();

		$sql = $sql->prepare("DELETE FROM forum_replies WHERE id = ? AND user_id = ? LIMIT 1");
		$sql->execute(array($id, $user->getId()));

		$this->success();
	}

	function getMyQuestions() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$last = isset($_GET['last']) ? (int)$_GET['last'] : 0;
		$user = new User();
		$sql = Database::languages();

		$sql = $sql->prepare("SELECT `id`, `replies`, `question` FROM `forum_questions` WHERE `course` = ? AND `id` > ? AND `user_id` = ? ORDER BY `id` DESC LIMIT 20");
		$sql->execute(array($course, $last, $user->getId()));

		$this->successWithData($sql->fetchAll(PDO::FETCH_ASSOC));
	}

	function getQuestion() {
		$this->checkLogin();
		if (!isset($_GET['id'])) $this->badRequest();
		$questionId = (int)$_GET['id'];

		$sql = Database::languages();
		$sql = $sql->prepare("SELECT `user_id`, `date`, `content`, `name`, `avatar` FROM `forum_questions` INNER JOIN `users` ON `forum_questions`.`id` = ? LIMIT 1");
		$sql->execute(array($questionId));
		
		$this->successWithData($sql->fetch(PDO::FETCH_ASSOC));
	}

	function getQuestions() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$last = isset($_GET['last']) ? (int)$_GET['last'] : 0;
		$sql = Database::languages();

		$sql = $sql->prepare("SELECT `id`, `replies`, `question` FROM `forum_questions` WHERE `course` = ? AND `id` > ? ORDER BY `id` DESC LIMIT 20");
		$sql->execute(array($course, $last));
		
		$this->successWithData($sql->fetchAll(PDO::FETCH_ASSOC));
	}

	function getReplies() {
		$this->checkLogin();
		if (!isset($_GET['id'])) $this->badRequest();
		$questionId = (int)$_GET['id'];
		$last = isset($_GET['last']) ? (int)$_GET['last'] : 0;

		$pdo = Database::languages();
		$sql = $pdo->prepare("SELECT `forum_replies`.`id`, `user_id`, `date`, `reply`, `name`, `avatar` FROM `forum_replies` INNER JOIN `users` ON `question_id` = ? AND `forum_replies`.`id` > ? LIMIT 20");
		$sql->execute(array($questionId, $last));
		
		$this->successWithData($sql->fetchAll(PDO::FETCH_ASSOC));
	}

	function searchQuestions() {
		$this->checkLogin();
		if (!isset($_GET['text'])) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$last = isset($_GET['last']) ? (int)$_GET['last'] : 0;
		$text = (string)$_GET['text'];
		$sql = Database::languages();

		$sql = $sql->prepare("SELECT `id`, `replies`, `question` FROM `forum_questions` WHERE `question` LIKE '%$text%' AND `course` = ? AND `id` > ? LIMIT 20");
		$sql->execute(array($course, $last));

		$this->successWithData($sql->fetchAll(PDO::FETCH_ASSOC));
	}

	function validateQuestion($question) {
		$question = (string)$question;
		$question = strip_tags($question);
		$question = trim($question);
		$question = preg_replace('/( )+/', ' ', $question);
		$length = strlen($question);
		if ($length < 18 or $length > 132) $this->badRequest();
		return $question;
	}

	function validateQuestionContent($content) {
		$content = (string)$content;
		$content = strip_tags($content, '<a><l><p>');
		$content = trim($content);
		$content = preg_replace('/( )+/', ' ', $content);
		$length = strlen($content);
		if ($length < 18 or $length > 2000) $this->badRequest();
		return $content;
	}

	function validateReply($reply) {
		$reply = (string)$reply;
		$reply = strip_tags($reply, '<a><l><p>');
		$reply = trim($reply);
		$reply = preg_replace('/( )+/', ' ', $reply);
		$length = strlen($reply);
		if ($length < 20 or $length > 2000) $this->badRequest();
		return $reply;
	}
}

?>