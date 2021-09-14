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
	<consts course="<?php echo($course); ?>" dicio="<?php echo($dicio); ?>" path="<?php echo(PATH); ?>"></consts>
</head>

<body>

<section id="loading"></section><!--loading-->

<section id="lightbox" style="display: none;">
<div class="container">
	<div class="header">
		<h1></h1>
	</div><!--header-->
	<div class="body">
	</div><!--body-->
	<div class="footer">
		<a id="ok" class="btn1" style="display: none;"><?php $lang->echo('ok'); ?></a>
		<a id="close" class="btn1"><?php $lang->echo('cls'); ?></a>
	</div><!--footer-->
</div><!--container-->
</section><!--lightbox-->

<section id="messages" class="hide">
	<div class="container">
		<p></p>
	</div><!--container-->
</section><!--messages-->

<section id="header">

	<div class="container">
		<nav id="menu">
			<a class="menu-btn" href="#"><i class="fas fa-bars"></i></a>
			<div class="menu-box">
				<div class="menu-header">
					<h1></h1>
					<h2><?php echo mb_strtoupper($lang->get($course), 'UTF-8'); ?></h2>
				</div>
				<div class="menu-middle">
					<a post="vocabulary" href="<?php echo(PATH.'/vocabulario') ?>"><i class="fas fa-brain"></i><?php $lang->echo('vcblry'); ?></a>
					<a post="modules" href="<?php echo(PATH.'/modulos') ?>"><i class="fas fa-book"></i><?php $lang->echo('mdls'); ?></a>
					<a post="shadowing" href="<?php echo(PATH.'/shadowing') ?>"><i class="fas fa-file-audio"></i>Shadowing</a>
					<a post="forum" href="<?php echo(PATH.'/forum') ?>"><i class="fab fa-forumbee"></i><?php $lang->echo('frm'); ?></a>
					<!-- <a post="chat" href="<?php echo(PATH.'/chat') ?>"><i class="fas fa-comments"></i>N'Talk</a> -->
					<a post="notifications" href="<?php echo(PATH.'/notificacoes') ?>"><i class="fas fa-bell"></i><?php $lang->echo('ntfctns'); ?></a>
					<!-- <a post="support" href="<?php echo(PATH.'/suporte') ?>"><i class="fas fa-envelope"></i><?php $lang->echo('sprt'); ?></a> -->
					<a post="settings" href="<?php echo(PATH.'/configuracoes') ?>"><i class="fas fa-cog"></i><?php $lang->echo('stngs'); ?></a>
					<?php if (!$premium) { ?>
						<a post="premium" href="<?php echo(PATH.'/premium') ?>"><i class="fas fa-smile"></i>Premium</a>
					<?php } ?>
				</div>
				<div class="menu-footer">
					<a post="logout" href="<?php echo(PATH.'/logout') ?>"><i class="fas fa-sign-out-alt"></i><?php $lang->echo('ext'); ?></a>
				</div>
			</div><!--menu-box-->
		</nav><!--menu-->

		<div class="logo">
			<a post="home"><div class="logo-img"></div></a>
		</div><!--logo-->

		<nav id="dropdown">
			<a class="dropdown-btn" href="#"><i class="fas fa-ellipsis-v"></i></a>
			<div class="dropdown-box">
				<a post="vocabulary" href="<?php echo(PATH.'/vocabulario') ?>"><?php $lang->echo('vcblry'); ?></a>
				<a post="shadowing" href="<?php echo(PATH.'/shadowing') ?>">Shadowing</a>
				<!-- <a post="chat" href="<?php echo(PATH.'/chat') ?>">N'Talk</a> -->
				<a post="modules" href="<?php echo(PATH.'/modulos') ?>"><?php $lang->echo('mdls'); ?></a>
			</div><!--dropdown-box-->
		</nav><!--dropdown-->
	</div><!--container-->

</section><!--header-->