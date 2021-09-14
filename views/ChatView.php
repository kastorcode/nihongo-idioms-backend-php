<?php

namespace views;
use views\MainView;

class ChatView extends MainView
{
	function render() {
		global $lang;
		require('php/chat.php');
	}
}

?>