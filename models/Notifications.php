<?php

namespace models;
use PDO;
use models\Database;
use models\User;

class Notifications extends MainModel
{
	function clearNotifications() {
		$this->checkLogin();
		$user = new User();
		$userId = $user->getId();

		$pdo = Database::languages();
		$sql = $pdo->prepare("DELETE FROM `my_notifications` WHERE `user_id` = ?");
		$sql->execute(array($userId));

		$sql = $pdo->prepare("UPDATE `settings` SET `notifications` = ? WHERE `user_id` = ? LIMIT 1");
		$sql->execute(array(0, $userId));

		$this->success();
	}

	function checkNotifications() {
		$this->checkLogin();

		$user = new User();
		$sql = Database::languages();

		$sql = $sql->prepare("SELECT notifications FROM settings WHERE user_id = ? LIMIT 1");
		$sql->execute(array($user->getId()));
		$data = $sql->fetch(PDO::FETCH_ASSOC);

		$this->successWithData($data);
	}

	function getNotification() {
		$this->checkLogin();
		if (!isset($_GET['id']) or !isset($_GET['type'])) $this->badRequest();

		$user = new User();
		$notificationId = (int)$_GET['id'];
		$type = (string)$_GET['type'];
		$pdo = Database::languages();

		switch ($type) {
			case 'my': {
				$sql = $pdo->prepare("SELECT `content` FROM `my_notifications` WHERE `id` = ? AND `user_id` = ? LIMIT 1");
				$sql->execute(array($notificationId, $user->getId()));
				$data = $sql->fetch(PDO::FETCH_ASSOC);
				$sql = $pdo->prepare("DELETE FROM `my_notifications` WHERE `id` = ? AND `user_id` = ? LIMIT 1");
				$sql->execute(array($notificationId, $user->getId()));
				break;
			}

			case 'notice': {
				$sql = $pdo->prepare("SELECT `content` FROM `notifications` WHERE `id` = ? LIMIT 1");
				$sql->execute(array($notificationId));
				$data = $sql->fetch(PDO::FETCH_ASSOC);
				break;
			}
			
			default: $this->badRequest();
		}

		$this->successWithData($data);
	}

	function getNotifications() {
		$this->checkLogin();
		$user = new User();

		$pdo = Database::languages();
		$sql = $pdo->prepare("SELECT `id`, `title` FROM `my_notifications` WHERE `user_id` = ?");
		$sql->execute(array($user->getId()));
		$data['my'] = $sql->fetchAll(PDO::FETCH_ASSOC);

		$sql = $pdo->prepare("SELECT `id`, `date`, `title` FROM `notifications`");
		$sql->execute();
		$data['notices'] = $sql->fetchAll(PDO::FETCH_ASSOC);
		
		$this->successWithData($data);
	}
}

?>