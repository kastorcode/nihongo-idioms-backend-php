<!DOCTYPE html>
<html>

<head>
	<meta charset='utf-8' />
	<title><?php echo($this->getTitle()); ?></title>
	<link rel='icon' href="<?php echo(PATH_IMAGES.'/logo_flat.png') ?>" type='image/x-icon' />
	<meta name='viewport' content='width=device-width, initial-scale=1.0' />
	<link href="<?php echo(PATH_CSS.'/logout.css'); ?>" rel='stylesheet' />
	<meta name='author' content='Matheus Ramalho de Oliveira' />
	<meta name="google-signin-client_id" content="207077854831-ncqrvt9c2u9s6dmgm4ppfijbrchv4kj7.apps.googleusercontent.com" />
</head>

<body>

<section id="login">

	<div class="box-logo">
		<a href="<?php echo(PATH); ?>"><div class="logo-img"></div></a>
	</div>

	<div class="box-login">
		<a href="#" class="g-signin2 button" data-onsuccess="onSignIn"></a>
	</div>

</section><!--login-->

<script src="<?php echo(PATH_JS.'/jquery.min.js') ?>"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>

<script>

function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    var googleToken = googleUser.getAuthResponse().id_token;

    $.post('index.php', {
    	url: 'enter',
    	googleToken: googleToken

    }, function(){
    	window.location.reload();
    });
}

</script>

</body>
</html>