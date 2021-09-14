<?php

namespace views;
use views\MainView;

class ShadowingView extends MainView
{
	function render() {
		global $lang;
		require('php/shadowing.php');
	}
}

?>