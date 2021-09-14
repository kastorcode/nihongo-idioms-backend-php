<?php

namespace views;
use views\MainView;

class ModulesView extends MainView
{
	function render() {
		require('php/modules.php');
	}
}

?>