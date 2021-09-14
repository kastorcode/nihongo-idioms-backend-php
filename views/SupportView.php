<?php

namespace views;
use views\MainView;

class SupportView extends MainView
{
	function render() {
		global $lang;
		require('php/support.php');
	}

	function renderTutorial() {
		global $lang;
		require('php/tutorial.php');
	}
}

?>