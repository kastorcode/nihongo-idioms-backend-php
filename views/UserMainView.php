<?php

namespace views;
use views\MainView;

class UserMainView extends MainView
{
	function render() {
		global $lang;
		require('php/user-main.php');
	}
}

?>