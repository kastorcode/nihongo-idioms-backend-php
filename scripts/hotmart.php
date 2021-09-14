<?php
	use models\User;

	$hottok = 'kLkm9OU4qJLVVvvPW2cS5cbEuJwweO10335431';
	$prod = 694977;

	/* A
	$file = fopen('teste.txt', 'w');
	fwrite($file, (string)$_POST);
	fclose($file);
	B */

	if (!isset($_POST['hottok']) or ($_POST['hottok'] != $hottok) or
		!isset($_POST['prod']) or ($_POST['prod'] != $prod) or
		!isset($_POST['status']) or ($_POST['status'] != 'approved')) { die();

	} else {
		$user = new User();
		$user->applyPremium($_POST['email']);
	}
?>