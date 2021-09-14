<?php

namespace models;
use PDO;
use models\Database;
use models\Settings;
use models\Vocabulary;

class User extends MainModel
{
	function createId() {
		$idExistent = true;
		$pdo = Database::languages();
		while ($idExistent) {
			$id = uniqid();
			$sql = $pdo->prepare("SELECT id FROM users WHERE id = ? LIMIT 1");
			$sql->execute(array($id));
			if (!$sql->rowCount()) {
				$idExistent = false;
			}
		}
		return $id;
	}

	/* function delete() {
		// Apagar tabela de frases
		$userTable = 'user_' + $this->getId();
		$sql = Database::phrases();
		$sql = $sql->prepare("DROP TABLE `$userTable`");
		$sql->execute();

		// Apaga o usuário
		$sql = Database::languages();
		$sql = $sql->prepare("DELETE FROM `users` WHERE `id` = ? LIMIT 1");
		$sql->execute(array($this->getId()));
		
		$this->logout();
	} */

	function deleteSession($pdo, $session) {
		$sql = $pdo->prepare("DELETE FROM sessions WHERE session = ? LIMIT 1");
		$sql->execute(array($session));
	}

	function getId() {
		return $_SESSION['id'];
	}

	function getType() {
		return $_SESSION['type'];
	}

	function login() {
		if (!isset($_GET['googleToken'])) $this->badRequest();
		$googleToken = (string)$_GET['googleToken'];
		$url = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token='.$googleToken;
		$object = file_get_contents($url);
		if ($object == false) $this->badRequest();
		$object = json_decode($object);

		$email = (string)$object->email;
		$pdo = Database::languages();
		$sql = $pdo->prepare("SELECT `id`, `type`, `name`, `avatar`, `gender`, `theme`, `auto`, `repro`, `course`, `my_courses`, `ads`, `premium`, `premium_date` FROM `users` INNER JOIN `settings` INNER JOIN `premium` ON `email` = ? LIMIT 1");
		$sql->execute(array($email));
		$user = $sql->fetch(PDO::FETCH_ASSOC);

		if ($user) {
			session_destroy();
			$data['auth']['session'] = session_create_id($user['id']);
			ini_set('session.use_cookies', 0);
			ini_set('session.use_trans_sid', 1);
			session_id($data['auth']['session']);
			session_start();

			$sql = $pdo->prepare("INSERT INTO sessions (user_id, session, last_login) VALUES (?, ?, ?)");
			$sql->execute(array($user['id'], $data['auth']['session'], date('Y-m-d')));
			$this->setSession($user);

			global $lang;
			$settings = new Settings();
			$vocabulary = new Vocabulary();
			$data['ads'] = $user['ads'];
			$data['auth']['logged'] = true;
			$data['auth']['id'] = $user['id'];
			$data['auth']['premium'] = $_SESSION['premium'];
			$data['user']['auto'] = $user['auto'];
			$data['user']['avatar'] = $user['avatar'];
			$data['user']['course']['short'] = $user['course'];
			$data['user']['course']['full'] = $lang->get($user['course']);
			$data['user']['course']['dicio'] = $settings->getDicio($user['course']);
			$data['user']['gender'] = $user['gender'];
			$data['user']['myCourses'] = $settings->getMyCourses($user['my_courses']);
			$data['user']['name'] = $user['name'];
			$data['user']['repro'] = $user['repro'];
			$data['user']['theme'] = $user['theme'];
			$this->successWithData($data);
		}
		else {
			$this->register($object);
		}
	}

	function logout() {
		$this->checkLogin();
		$session = (string)$_GET['session'];
		$pdo = Database::languages();
		$this->deleteSession($pdo, $session);
		session_destroy();
		$this->success();
	}

	function refreshSession() {
		$session = (string)$_GET['session'];
		$pdo = Database::languages();

		$sql = $pdo->prepare("SELECT sessions.user_id AS id, sessions.last_login, users.type, premium.premium, premium.premium_date FROM sessions INNER JOIN users INNER JOIN premium ON session = ? LIMIT 1");
		$sql->execute(array($session));
		$data = $sql->fetch(PDO::FETCH_ASSOC);

		if ($data) {
			if (date('Y-m-d', strtotime($data['last_login'].' +365 days')) < date('Y-m-d')) {
				$this->deleteSession($pdo, $session);
			}
			else {
				$this->updateLastLogin($pdo, $session);
				$this->setSession($data);
			}
		}
	}

	function register($object) {
		$id = $this->createId();
		$name = (string)$object->name;
		$email = (string)$object->email;
		$avatar = explode('://', $object->picture)[1];

		$pdo = Database::languages();
		$sql = $pdo->prepare("INSERT INTO `users` VALUES (?,?,?,?,?,?,?,?)");
		$sql->execute(array($id, 0, $name, $email, $avatar, 'M', 0));

		$sql = $pdo->prepare("INSERT INTO `settings` VALUES (?,?,?,?,?,?,?,?)");
		$sql->execute(array($id, 0, 0, 0, 'en', 'en', 1, 1));

		$sql = $pdo->prepare("INSERT INTO `premium` VALUES (?,?,?,?)");
		$sql->execute(array($id, 0, 0, null));

		$pdo = Database::phrases();
		$sql = $pdo->prepare("CREATE TABLE `user_$id` (`phrase_id` INT NOT NULL, `course` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `revisions` INT NOT NULL DEFAULT 0, `factor` INT NOT NULL DEFAULT 1, `review` DATE NOT NULL, `last_revision` DATE NOT NULL DEFAULT '0000-00-00', `sync` BOOLEAN NOT NULL DEFAULT TRUE, PRIMARY KEY (phrase_id, course)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci");
		$sql->execute();

		$this->login();
	}

	function setSession($sql) {
		$_SESSION['id'] = $sql['id'];
		$_SESSION['type'] = (int)$sql['type'];
		$_SESSION['premium'] = (int)$sql['premium'];
		$_SESSION['premium_date'] = $sql['premium_date'];
	}

	function updateLastLogin($pdo, $session) {
		$sql = $pdo->prepare("UPDATE sessions SET last_login = ? WHERE session = ? LIMIT 1");
		$sql->execute(array(date('Y-m-d'), $session));
	}
}

?>