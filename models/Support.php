<?php

namespace models;
use PDO;
use models\Database;
use models\User;

class Support extends MainModel
{
	function addMessage() {
		$user = new User();
		if (!$user->logged() or !isset($_POST['message'])) die();

		$message = $this->validateMessage((string)$_POST['message']);	
		switch ($user->getType()) {
			case 0:
				$sent = $user->getId();
				$received = null;
			break;
			
			default:
				$sent = null;
				$received = $user->getId();
			break;
		}

		$sql = Database::languages();
		$sql = $sql->prepare("INSERT INTO `support` (`id`, `sent`, `received`, `message`) VALUES (?,?,?,?)");
		$sql->execute(array(null, $sent, $received, $message));
		die();
	}

	function clearSupport() {
		$user = new User();
		if (!$user->logged()) die();

		$sql = Database::languages();
		$sql = $sql->prepare("UPDATE `settings` SET `support` = ? WHERE `user_id` = ? LIMIT 1");
		$sql->execute(array(0, $user->getId()));
		die();
	}

	function getMessages() {
		$user = new User();
		if (!$user->logged()) die();
		$last = isset($_POST['last']) ? (int)$_POST['last'] : 0;
		$userId = $user->getId();

		$sql = Database::languages();
		$sql = $sql->prepare("SELECT `id`, `sent`, `message` FROM `support` WHERE (`sent` = ? OR `received` = ?) AND `id` > ?");
		$sql->execute(array($userId, $userId, $last));
		die(json_encode($sql->fetchAll(PDO::FETCH_ASSOC)));
	}

	function getTutorial() {
		$sql = Database::languages();
		$sql = $sql->prepare("SELECT topic, icon, title, `text` FROM tutorial");
		$sql->execute();
		$data['tutorial'] = $sql->fetchAll(PDO::FETCH_ASSOC);
		$this->successWithData($data);
	}

	function validateMessage($str) {
		$str = strip_tags($str, '<audio><iframe><img><video>');
		$str = trim($str);
		$str = preg_replace('/( )+/', ' ', $str);
		$length = strlen($str);
		if (($length < 18) or ($length > 2000)) die();
		return $str;
	}
}

?>