<?php
	
namespace views;
use models\Settings;
use models\User;

class MainView
{
	private $title;

	function __construct() {
		global $lang;
		$this->title = $lang->get('logo');
	}

	function getPagination() {
		$step = 50;
		$page['start'] = ($_SESSION['page'] - 1) * $step;
		$page['end'] = $step;
		$_SESSION['page'] += 1;
		return $page;
	}

	function getTitle() {
		return $this->title;
	}

	function setTitle($title) {
		$this->title = (string)$title;
	}

	function renderAdBlock() {
		global $lang;
		require('php/ad-block.php');
	}

	function renderBase() {
		global $lang;
		$user = new User();
		$premium = $user->itsPremium();
		$settings = new Settings();
		$course = $settings->getCourse();
		$dicio = $settings->getDicio();
		require('php/header.php');
		require('php/body.php');
	}

	function renderSimpleBase() {
		global $lang;
		$user = new User();
		$logged = $user->logged();
		require('php/simple-header.php');
		require('php/simple-body.php');
	}
}

?>