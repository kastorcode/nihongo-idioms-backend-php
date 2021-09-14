<!DOCTYPE html>
<html>

<head>
	<meta charset='utf-8' />
	<title><?php echo($this->getTitle()) ?></title>
	<link rel='icon' href="<?php echo(PATH_IMAGES.'/logo_flat.png') ?>" type='image/x-icon' />
	<meta name='viewport' content='width=device-width, initial-scale=1.0' />
	<link href="<?php echo(PATH_CSS.'/all.min.css') ?>" rel='stylesheet' />
	<link href="<?php echo(PATH_CSS.'/jquery-ui.min.css') ?>" rel='stylesheet' />
	<link href="<?php echo(PATH_CSS.'/login.css') ?>" rel='stylesheet' />
	<meta name='author' content='Matheus Ramalho de Oliveira' />
	<consts path="<?php echo(PATH); ?>"></consts>
</head>

<body>

<section id="header">

	<div class="container">

		<div class="logo">
			<a href="<?php echo(PATH); ?>"><div class="logo-img"></div></a>
		</div><!--logo-->
		
		<nav id="simple">
			<?php if ($logged) { ?>
				<a id="close" href="#" title="<?php $lang->echo('cls'); ?>"><i class="far fa-window-close"></i></a>
			<?php } else { ?>
				<a href="<?php echo(PATH.'/login'); ?>" title="<?php $lang->echo('lgn'); ?>"><i class="fas fa-sign-in-alt"></i></a>
			<?php } ?>
		</nav><!--simple-->

	</div><!--container-->

</section><!--header-->