<?php

namespace views;
use models\Vocabulary;
use views\MainView;

class VocabularyView extends MainView
{
	function render() {
		global $lang;
		require('php/vocabulary.php');
	}

	function renderMyPhrases() {
		global $lang;
		$vocabulary = new Vocabulary();
		$total = $vocabulary->getTotal();
		$phrases = $vocabulary->getMyPhrases();
		require('php/my-phrases.php');
	}

	function renderSearchPhrases() {
		global $lang;
		require('php/search-phrases.php');
	}
}

?>