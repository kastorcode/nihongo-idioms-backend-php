<link href="<?php echo(PATH_CSS.'/owl.carousel.min.css') ?>" rel='stylesheet' />
<link href="<?php echo(PATH_CSS.'/owl.theme.default.min.css') ?>" rel='stylesheet' />

<section id="settings">

	<section id="settings-box">
		<img id="avatar" src="" />
		<div class="bool">
			<p><?php echo $lang->get('actvPrm').':'; ?></p>
			<p><?php if ($itsPremium) { $lang->echo('yes'); } else { $lang->echo('not'); } ?></p>
		</div>
		<?php if ($itsPremium) { ?>
		<div class="bool">
			<p><?php echo $lang->get('vldUntl').':'; ?></p>
			<p><?php echo $premiumDate; ?></p>
		</div>
		<?php } ?>

		<div class="owl-carousel">
		<div>
			<img src="<?php echo PATH_IMAGES.'/7.jpg' ?>" />
			<div class="box"></div>
			<p><?php echo $lang->get('prmAcs').'.'; ?></p>
		</div>
		<div>
	  		<img src="<?php echo PATH_IMAGES.'/5.jpg' ?>" />
	  		<div class="box"></div>
	  		<p><?php echo $lang->get('rmvAds').'.'; ?></p>
	    </div>
		<div>
			<img src="<?php echo PATH_IMAGES.'/1.jpg' ?>" />
			<div class="box"></div>
			<p><?php echo $lang->get('usOfln').'.'; ?></p>
		</div>
	  	<div>
	  		<img src="<?php echo PATH_IMAGES.'/2.jpg' ?>" />
	  		<div class="box"></div>
	  		<p><?php echo $lang->get('hlpPltfrm').'!'; ?></p>
	  	</div>
	  	<div>
			<img src="<?php echo PATH_IMAGES.'/4.jpg' ?>" />
			<div class="box"></div>
			<p><?php echo $lang->get('sprtEdctn').'!'; ?></p>
		</div>
		<div>
			<img src="<?php echo PATH_IMAGES.'/3.jpg' ?>" />
			<div class="box"></div>
			<p><?php echo $lang->get('goTgthr').'!'; ?></p>
		</div>
	</div>

	<a class="btn3" target="_blank" href="<?php echo(PATH.'/tutorial#premium') ?>"><i class="far fa-question-circle"></i><?php $lang->echo('hlp'); ?></a>
	<a class="btn3" target="_blank" href="#"><i class="far fa-smile"></i><?php $lang->echo('prchs'); ?></a>

	</section><!--settings-box-->
</section><!--settings-->

<script src="<?php echo(PATH_JS.'/owl.carousel.min.js') ?>"></script>

<script>

$(document).ready(function(){

$('#avatar').attr('src', 'https://' + user.avatar);

$('.owl-carousel').owlCarousel({
	autoHeight: true,
	center: true,
	items: 1,
    loop: true,
    margin: 8,
    stagePadding: 16,
    autoplay: true,
    autoplayTimeout: 4000,
    autoplayHoverPause: true,
    responsive:{
	    767:{
	    	margin: 10,
    		stagePadding: 40,
        }
    }
});

});

</script>