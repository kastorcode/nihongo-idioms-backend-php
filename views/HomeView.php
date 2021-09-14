<?php

namespace views;
use views\MainView;

class HomeView extends MainView
{
	function renderHome() {
		global $lang;
		require('php/home.php');
	}
}

?>