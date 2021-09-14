<?php

namespace models;
use models\Database;
use PDO;

class UserMain extends MainModel
{
	function getMainAd() {
		$pdo = Database::languages();
		$sql = $pdo->prepare("SELECT id, img FROM main_ad ORDER BY RAND()");
		$sql->execute();
		$data['ad'] = $sql->fetchAll(PDO::FETCH_ASSOC);

		if ($data['ad']) {
			$sql = $pdo->prepare("UPDATE main_ad SET views = views + 1");
			$sql->execute();
		}

		$this->successWithData($data);
	}

	function mainAdClick() {
		$id = (int)$_GET['id'];
		$pdo = Database::languages();

		$sql = $pdo->prepare("SELECT link FROM main_ad WHERE id = ? LIMIT 1");
		$sql->execute(array($id));
		$link = $sql->fetch(PDO::FETCH_ASSOC)['link'];

		if ($link) {
			$sql = $pdo->prepare("UPDATE main_ad SET clicks = clicks + 1 WHERE id = ? LIMIT 1");
			$sql->execute(array($id));
			$this->redirect($link);
		}
		else {
			$this->redirect();
		}
	}
}

?>