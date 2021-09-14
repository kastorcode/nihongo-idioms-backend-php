<?php

namespace models;
use PDO;
use models\Database;
use models\Settings;

class Vocabulary extends MainModel
{
	function addExistingPhrase() {
		$this->checkLogin();
		if (!isset($_GET['id'])) $this->badRequest();

		$phraseId = (int)$_GET['id'];
		$course = $this->validateCourse($_GET['course']);
		if ($this->phraseAdded($phraseId, $course)) $this->success();
		if (!$this->existingId($course, $phraseId)) $this->badRequest();

		$userTable = $this->getUserTable();
		$sql = Database::phrases();
		$sql = $sql->prepare("INSERT INTO `$userTable` VALUES (?,?,?,?,?,?,?)");

		if ($sql->execute(array($phraseId, $course, 0, 1, date('Y-m-d'), '0000-00-00', 1)))
			$this->success();
		else
			$this->internalServerError();
	}

	function addPhrase() {
		$this->checkLogin();
		if (!isset($_GET['phrase']) or !isset($_GET['translation'])) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$phrase = $this->validatePhrase($_GET['phrase']);
		$translation = $this->validatePhrase($_GET['translation']);
		$exist = $this->existingPhrase($course, $phrase);

		if (!$exist) {
			// Adiciona frase a tabela de frases
			$table = $this->getCourseTable($course);
			$pdo = Database::phrases();
			$sql = $pdo->prepare("INSERT INTO `$table` VALUES (?,?,?)");
			$sql->execute(array(null, $phrase, $translation));
			$phraseId = $pdo->lastInsertId();
			$audio = $this->generateAudio($phraseId, $course, $phrase);

		} else { $phraseId = $exist['id']; }

		if (!$this->phraseAdded($phraseId, $course)) {			
			// Adiciona frase a tabela do usuário
			$table = $this->getUserTable();
			$sql = Database::phrases();
			$sql = $sql->prepare("INSERT INTO `$table` VALUES (?,?,?,?,?,?,?)");
			$sql->execute(array($phraseId, $course, 0, 1, date('Y-m-d'), '0000-00-00', 1));
		}

		$this->success();
	}

	function backReview($factor) {
		if ($factor < 11) {
			if ($factor > 1) $factor -= 1;
			return [$factor, date('Y-m-d', strtotime('+1 day'))];

		} else {
			return [$factor -= 30, date('Y-m-d', strtotime('+1 day'))];
		}
	}

	function deletePhrase() {
		$this->checkLogin();
		if (!isset($_GET['id'])) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$phraseId = (int)$_GET['id'];
		$userTable = $this->getUserTable();
		$sql = Database::phrases();
		$sql = $sql->prepare("DELETE FROM `$userTable` WHERE `phrase_id` = ? AND `course` = ? LIMIT 1");

		if ($sql->execute(array($phraseId, $course)))
			$this->success();
		else
			$this->internalServerError();
	}

	function existingId($course, $id) {
		$table = $this->getCourseTable($course);
		$sql = Database::phrases();
		$sql = $sql->prepare("SELECT `id` FROM `$table` WHERE `id` = ? LIMIT 1");
		$sql->execute(array($id));
		return $sql->rowCount();
	}

	function existingPhrase($course, $phrase) {
		$table = $this->getCourseTable($course);
		$sql = Database::phrases();
		$sql = $sql->prepare("SELECT `id` FROM `$table` WHERE `phrase` = ? LIMIT 1");
		$sql->execute(array($phrase));
		return $sql->fetch();
	}

	function finishSyncDB() {
		$this->checkLogin();
		if (!isset($_SESSION['syncDB'])) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$userTable = $this->getUserTable();
		$sql = Database::phrases();

		$sql = $sql->prepare("UPDATE `$userTable` SET sync = ? WHERE course = ? AND sync = ?");
		$sql->execute(array(0, $course, 1));
		unset($_SESSION['syncDB']);

		$this->success();
	}

	function generateAudio($id, $course, $text) {
		$data = (string)json_encode(['engine' => 'Google', 'data' => ['voice' => $this->getVoice($course), 'text' => $text]]);
		$curl = curl_init('https://api.soundoftext.com/sounds');
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		if (!$response->success) return false;

		$curl = curl_init('https://api.soundoftext.com/sounds/'.$response->id);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		if ($response->status != 'Done') return false;

		$response = file_get_contents($response->location);
		$file = fopen($this->getAudioDir($id, $course), 'w');
		fwrite($file, $response);
		fclose($file);
		return true;
	}

	function getAudioDir($id, $course) {
		return DIR.'/files/vocabulary/'.$course.'/'.$id.'.mp3';
	}

	function getPhrases() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$last = isset($_GET['last']) ? (int)$_GET['last'] : 0;
		$courseTable = $this->getCourseTable($course);
		$userTable = $this->getUserTable();
		$pdo = Database::phrases();

		$sql = $pdo->prepare("SELECT `phrase_id`, `revisions`, `factor` FROM `$userTable` WHERE `phrase_id` > ? AND `course` = ? AND `review` < ? LIMIT 10");
		$sql->execute(array($last, $course, date('Y-m-d', strtotime('+1 day'))));
		$phrases = $sql->fetchAll(PDO::FETCH_ASSOC);

		foreach ($phrases as $key => $value) {
			$sql = $pdo->prepare("SELECT `phrase`, `translation` FROM `$courseTable` WHERE `id` = ? LIMIT 1");
			$sql->execute(array($value['phrase_id']));
			$result = $sql->fetch(PDO::FETCH_ASSOC);
			$phrases[$key]['phrase'] = $result['phrase'];
			$phrases[$key]['translation'] = $result['translation'];
		}

		$this->successWithData($phrases);
	}

	function getRevisions() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$userTable = $this->getUserTable();
		$sql = Database::phrases();

		$sql = $sql->prepare("SELECT `phrase_id` FROM `$userTable` WHERE `course` = ? AND `review` < ?");
		$sql->execute(array($course, date('Y-m-d', strtotime('+1 day'))));
		$data['revisions'] = $sql->rowCount();

		$this->successWithData($data);
	}

	function getCourseTable($course) {
		return 'course_'.$course;
	}

	function getLearnedPhrases() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$userTable = $this->getUserTable();
		$courseTable = $this->getCourseTable($course);
		$last = isset($_GET['last']) ? (int)$_GET['last'] : 0;
		$pdo = Database::phrases();

		$sql = $pdo->prepare("SELECT `phrase_id`, `revisions`, `last_revision` FROM `$userTable` WHERE `course` = ? AND `phrase_id` > ? AND `factor` > ? LIMIT 20");
		$sql->execute(array($course, $last, 30));
		$phrases = $sql->fetchAll(PDO::FETCH_ASSOC);

		foreach ($phrases as $key => $value) {
			$sql = $pdo->prepare("SELECT `phrase` FROM `$courseTable` WHERE `id` = ? LIMIT 1");
			$sql->execute(array($value['phrase_id']));
			$phrases[$key]['phrase'] = $sql->fetch(PDO::FETCH_ASSOC)['phrase'];
		}

		$this->successWithData($phrases);
	}

	function getMyPhrases() {
		$userTable = $this->getUserTable();
		$courseTable = $this->getCourseTable();
		$settings = new Settings();

		$pdo = Database::phrases();
		$sql = $pdo->prepare("SELECT `phrase_id`, `revisions`, `last_revision` FROM `$userTable` WHERE `course` = ? LIMIT 20");
		$sql->execute(array($settings->getCourse()));
		$phrases = $sql->fetchAll(PDO::FETCH_ASSOC);

		foreach ($phrases as $key => $value) {
			$sql = $pdo->prepare("SELECT `phrase` FROM `$courseTable` WHERE `id` = ? LIMIT 1");
			$sql->execute(array($value['phrase_id']));
			$phrases[$key]['phrase'] = $sql->fetch(PDO::FETCH_ASSOC)['phrase'];
		}

		return $phrases;
	}

	function getTotal() {
		$userTable = $this->getUserTable();
		$settings = new Settings();

		$sql = Database::phrases();
		$sql = $sql->prepare("SELECT `phrase_id` FROM `$userTable` WHERE `course` = ?");
		$sql->execute(array($settings->getCourse()));
		return $sql->rowCount();
	}

	function getTotalLearned() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$userTable = $this->getUserTable();
		$sql = Database::phrases();

		$sql = $sql->prepare("SELECT `phrase_id` FROM `$userTable` WHERE `course` = ? AND `factor` > ?");
		$sql->execute(array($course, 30));
		$data['total'] = $sql->rowCount();
		
		$this->successWithData($data);
	}

	function getTotalPhrases() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$userTable = $this->getUserTable();
		$sql = Database::phrases();

		$sql = $sql->prepare("SELECT `phrase_id` FROM `$userTable` WHERE `course` = ?");
		$sql->execute(array($course));
		$data['total'] = $sql->rowCount();

		$this->successWithData($data);
	}

	function getUserTable() {
		$user = new User();
		return (string)'user_'.$user->getId();
	}

	function getVoice($course) {
		switch ($course) {
			case 'en': return 'en-US';
		}
	}

	function nextReview($factor) {
		if ($factor < 6) {
			return [$factor += 1, date('Y-m-d', strtotime('+1 day'))];

		} else if ($factor < 11) {
			return [$factor += 1, date('Y-m-d', strtotime('+7 days'))];

		} else if ($factor == 11) {
			return [30, date('Y-m-d', strtotime('+30 days'))];

		} else {
			$factor += 30;
			return [$factor, date('Y-m-d', strtotime('+'.$factor.' days'))];
		}
	}

	function phrase() {
		$this->checkLogin();
		if (!isset($_GET['phrase'])) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$phrase = json_decode($_GET['phrase']);
		$userTable = $this->getUserTable();
		$sql = Database::phrases();

		$sql = $sql->prepare("UPDATE `$userTable` SET `revisions` = ?,`factor` = ?, `review` = ?, `last_revision` = ?, sync = ? WHERE `phrase_id` = ? AND `course` = ? LIMIT 1");

		if (@$sql->execute(array($phrase->revisions, $phrase->factor, $phrase->review, $phrase->last_revision, 1, $phrase->phrase_id, $course)))
			$this->success();
		else
			$this->internalServerError();
	}

	function phraseAdded($phraseId, $course) {
		$table = $this->getUserTable();
		$sql = Database::phrases();
		$sql = $sql->prepare("SELECT `phrase_id` FROM `$table` WHERE `phrase_id` = ? AND `course` = ? LIMIT 1");
		$sql->execute(array($phraseId, $course));
		return $sql->rowCount();
	}

	function phraseAndTranslation() {
		$this->checkLogin();
		if (!isset($_GET['ids'])) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$courseTable = $this->getCourseTable($course);
		$pdo = Database::phrases();

		foreach ($_GET['ids'] as $value) {
			$sql = $pdo->prepare("SELECT `phrase`, `translation` FROM `$courseTable` WHERE `id` = ? LIMIT 1");
			$sql->execute(array($value));
			$data[$value] = $sql->fetch(PDO::FETCH_ASSOC);
		}

		$this->successWithData($data);
	}

	function searchMyPhrases() {
		$this->checkLogin();
		if (!isset($_GET['text'])) $this->badRequest();

		$text = (string)$_GET['text'];
		if (strlen($text) > 100) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$last = isset($_GET['last']) ? (int)$_GET['last'] : 0;
		$userTable = $this->getUserTable();
		$courseTable = $this->getCourseTable($course);
		$pdo = Database::phrases();

		$sql = $pdo->prepare("SELECT `id` as `phrase_id`, `phrase` FROM `$courseTable` WHERE `phrase` LIKE '%$text%' AND `id` > ? LIMIT 20");
		$sql->execute(array($last));
		$phrases = $sql->fetchAll(PDO::FETCH_ASSOC);

		foreach ($phrases as $key => $value) {
			$sql = $pdo->prepare("SELECT `revisions`, `last_revision` FROM `$userTable` WHERE `phrase_id` = ? AND `course` = ? LIMIT 1");
			$sql->execute(array($value['phrase_id'], $course));
			$result = $sql->fetch(PDO::FETCH_ASSOC);
			if ($result == false) {
				unset($phrases[$key]);
			}
			else {
				$phrases[$key]['revisions'] = $result['revisions'];
				$phrases[$key]['last_revision'] = $result['last_revision'];
			}
		}

		$this->successWithData(array_values($phrases));
	}

	function searchPhrases() {
		$this->checkLogin();
		if (!isset($_GET['text'])) $this->badRequest();

		$text = (string)$_GET['text'];
		if (strlen($text) > 100) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$last = isset($_GET['last']) ? (int)$_GET['last'] : 0;
		$courseTable = $this->getCourseTable($course);
		$sql = Database::phrases();

		$sql = $sql->prepare("SELECT `id`, `phrase`, `translation` FROM `$courseTable` WHERE `phrase` LIKE '%$text%' AND `id` > ? LIMIT 20");
		$sql->execute(array($last));
		$sql = $sql->fetchAll(PDO::FETCH_ASSOC);
		
		$this->successWithData($sql);
	}

	function syncAll() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$userTable = $this->getUserTable();
		$sql = Database::phrases();
		$sql = $sql->prepare("UPDATE `$userTable` SET sync = ? WHERE `course` = ?");

		if ($sql->execute(array(1, $course)))
			$this->success();
		else
			$this->internalServerError();
	}

	function syncDB() {
		$this->checkLogin();

		$course = $this->validateCourse($_GET['course']);
		$userTable = $this->getUserTable();
		$pdo = Database::phrases();

		if (isset($_GET['phrases'])) {
			foreach ($_GET['phrases'] as $value) {
				$value = json_decode($value);
				$sql = $pdo->prepare("UPDATE `$userTable` SET `revisions` = ?, `factor` = ?, `review` = ?, `last_revision` = ?, sync = ? WHERE `phrase_id` = ? AND `course` = ? AND `last_revision` < ? LIMIT 1");
				@$sql->execute(array($value->revisions, $value->factor, $value->review, $value->last_revision, 0, $value->id, $course, $value->last_revision));
			}
		}

		$sql = $pdo->prepare("SELECT phrase_id AS id, revisions, factor, review, last_revision FROM `$userTable` WHERE course = ? AND sync = ?");
		$sql->execute(array($course, 1));
		$data['phrases'] = $sql->fetchAll(PDO::FETCH_ASSOC);
		$_SESSION['syncDB'] = true;

		$this->successWithData($data);
	}

	function train() {
		$this->checkLogin();
		if (!isset($_GET['day'])) $this->badRequest();

		$course = $this->validateCourse($_GET['course']);
		$day = (int)$_GET['day'];

		switch ($day) {
			case 0: break;
			case 1: $date = date('Y-m-d', strtotime('-1 day')); break;
			case 2: $date = date('Y-m-d'); break;
			case 3: $date = date('Y-m-d', strtotime('+1 day')); break;		
			default: $this->badRequest();
		}

		$last = isset($_GET['last']) ? (int)$_GET['last'] : 0;
		$userTable = $this->getUserTable();
		$pdo = Database::phrases();

		switch ($day) {
			case 0: {
				$sql = $pdo->prepare("SELECT `phrase_id` FROM `$userTable` WHERE `course` = ? ORDER BY RAND() LIMIT 10");
				$sql->execute(array($course));
				break;
			}
			case 1: case 2: {
				$sql = $pdo->prepare("SELECT `phrase_id` FROM `$userTable` WHERE `phrase_id` > ? AND `last_revision` = ? AND `course` = ? LIMIT 10");
				$sql->execute(array($last, $date, $course));
				break;
			}
			case 3: {
				$sql = $pdo->prepare("SELECT `phrase_id` FROM `$userTable` WHERE `phrase_id` > ? AND `review` = ? AND `course` = ? LIMIT 10");
				$sql->execute(array($last, $date, $course));
				break;
			}
		}

		$phrases = $sql->fetchAll(PDO::FETCH_ASSOC);
		$courseTable = $this->getCourseTable($course);

		foreach ($phrases as $key => $value) {
			$sql = $pdo->prepare("SELECT `phrase`, `translation` FROM `$courseTable` WHERE `id` = ? LIMIT 1");
			$sql->execute(array($value['phrase_id']));
			$result = $sql->fetch(PDO::FETCH_ASSOC);
			$phrases[$key]['phrase'] = $result['phrase'];
			$phrases[$key]['translation'] = $result['translation'];
		}

		$this->successWithData($phrases);
	}

	function validatePhrase($phrase) {
		$phrase = (string)$phrase;
		$phrase = strip_tags($phrase);
		$phrase = trim($phrase);
		$phrase = preg_replace('/( )+/', ' ', $phrase);
		$length = strlen($phrase);
		if (($length < 14) or ($length > 100)) $this->badRequest();
		return $phrase;
	}
}

?>