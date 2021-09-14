<?php

namespace views;
use models\User;
use views\MainView;

class ForumView extends MainView
{
	function render() {
		global $lang;
		$user = new User();
		$logged = $user->logged();
		require('php/forum.php');
	}
}

?>