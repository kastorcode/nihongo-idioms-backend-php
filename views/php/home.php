<!DOCTYPE html>
<html>

<head>
	<meta charset='utf-8' />
	<title><?php echo($this->getTitle()); ?></title>
	<link rel='icon' href="<?php echo(PATH_IMAGES.'/logo_flat.png'); ?>" type='image/x-icon' />
	<meta name='viewport' content='width=device-width, initial-scale=1.0' />
	<link href="<?php echo(PATH_CSS.'/all.min.css'); ?>" rel='stylesheet' />
	<link href="<?php echo(PATH_CSS.'/owl.carousel.min.css'); ?>" rel='stylesheet' />
	<link href="<?php echo(PATH_CSS.'/owl.theme.default.min.css'); ?>" rel='stylesheet' />
	<link href="<?php echo(PATH_CSS.'/logout.css'); ?>" rel='stylesheet' />
	<meta name='author' content='Matheus Ramalho de Oliveira' />
	<meta name="google-signin-client_id" content="207077854831-ncqrvt9c2u9s6dmgm4ppfijbrchv4kj7.apps.googleusercontent.com" />
</head>

<body>

<section id="header">

	<div class="container">
		<div class="logo">
			<div class="logo-img"></div>
			<span class="name"><?php $lang->echo('logo'); ?></span>
		</div>

		<a href="#" class="g-signin2 button" data-onsuccess="onSignIn"></a>
	</div><!--container-->

</section><!--header-->

<section id="carousel">

	<div class="owl-carousel">
		<div>
			<p><?php $lang->echo('home1'); ?></p>
		</div>
		<div>
			<p><?php $lang->echo('home2'); ?></p>
		</div>
	  	<div>
	  		<p><?php $lang->echo('home3'); ?></p>
	  	</div>
	  	<div>
	  		<p><?php $lang->echo('home4'); ?></p>
	    </div>
	    <div>
	  		<p><?php $lang->echo('home5'); ?></p>
	    </div>
		<div>
			<p><?php $lang->echo('home6'); ?></p>
		</div>
		<div>
			<p><?php $lang->echo('home7'); ?></p>
		</div>
		<div>
			<p><?php $lang->echo('home8'); ?></p>
		</div>
	</div>

</section><!--carousel-->

<section id="footer">

	<div class="container">
		<a href="<?php echo(PATH.'/tutorial'); ?>"><?php $lang->echo('trl'); ?></a> | 
		<a href="<?php echo(PATH.'/login'); ?>"><?php $lang->echo('lgn'); ?></a>
	</div><!--container-->

</section><!--footer-->

<script src="<?php echo(PATH_JS.'/jquery.min.js'); ?>"></script>
<script src="<?php echo(PATH_JS.'/owl.carousel.min.js'); ?>"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>

<script>

function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    var googleToken = googleUser.getAuthResponse().id_token;
    console.log(googleUser);

    $.post('index.php', {
    	url: 'enter',
    	googleToken: googleToken

    }, function(){
    	//window.location.reload();
    });
}

$(document).ready(function(){

	$('.owl-carousel').owlCarousel({
		autoplay: true,
    	autoplayTimeout: 5000,
    	autoplayHoverPause: true,
		center: true,
		items: 1,
	    loop: true,
	    margin: 10,
	    nav: true,
	});

});

</script>

</body>
</html>