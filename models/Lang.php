<?php

namespace models;

class Lang
{
	private $file;

	function __construct() {
		$this->file = json_decode(file_get_contents('lang/pt.json'));
	}

	function echo($key) {
		echo $this->file->$key;
	}

	function get($key) {
		return $this->file->$key;
	}
}

?>