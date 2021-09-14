<?php

namespace views;
use views\MainView;

class LoginView extends MainView
{
	function render() {
		require('php/login.php');
	}
}

?>