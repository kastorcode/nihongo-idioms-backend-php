<?php

namespace views;
use PDO;
use models\Database;
use models\User;
use views\MainView;

class SettingsView extends MainView
{
	function render() {
		global $lang;
		$user = new User();
		$premium = $user->itsPremium();
		$settings = Database::languages();
		$settings = $settings->prepare("SELECT `gender`, `private` FROM `users` WHERE `id` = ? LIMIT 1");
		$settings->execute(array($user->getId()));
		$settings = $settings->fetch(PDO::FETCH_ASSOC);
		require('php/settings.php');
	}

	function renderPremium() {
		global $lang;
		$user = new User();
		$itsPremium = $user->itsPremium();
		if ($itsPremium) {
			$premiumDate = Database::languages();
			$premiumDate = $premiumDate->prepare("SELECT `premium_date` FROM `premium` WHERE `user_id` = ? LIMIT 1");
			$premiumDate->execute(array($user->getId()));
			$premiumDate = $premiumDate->fetch(PDO::FETCH_ASSOC)['premium_date'];
		}
		require('php/premium.php');
	}
}

?>