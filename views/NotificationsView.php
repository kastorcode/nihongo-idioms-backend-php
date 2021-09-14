<?php

namespace views;
use views\MainView;

class NotificationsView extends MainView
{
	function render() {
		global $lang;
		require('php/notifications.php');
	}
}

?>