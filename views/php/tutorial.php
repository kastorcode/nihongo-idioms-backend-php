<section id="settings">
	<h1><?php $lang->echo('hlpPg'); ?></h1>

	<section id="settings-box">

		<ul class="collapsible">
			<li>
		    	<p class="header"><?php echo $lang->get('hwStdy').'?'; ?></p>
		      	<p id="study" class="body"><?php $lang->echo('hwStdy2'); ?></p>
		    </li>
		    <li>
		    	<p class="header"><?php echo $lang->get('hwVcblry').'?'; ?></p>
		      	<p id="vocabulary" class="body"><?php $lang->echo('hwVcblry2'); ?></p>
		    </li>
		    <li>
		    	<p class="header"><?php echo $lang->get('hwShdwng').'?'; ?></p>
		      	<p id="shadowing" class="body"><?php $lang->echo('hwShdwng2'); ?></p>
		    </li>
		    <li>
		    	<p class="header"><?php echo $lang->get('whtMdls').'?'; ?></p>
		      	<p id="modules" class="body"><?php $lang->echo('whtMdls2'); ?></p>
		    </li>
		    <li>
		    	<p class="header"><?php $lang->echo('hwChng'); ?></p>
		      	<p id="change" class="body"><?php $lang->echo('hwChng2'); ?></p>
		    </li>
		    <li>
		    	<p class="header"><?php $lang->echo('hwAdd'); ?></p>
		      	<p id="add" class="body"><?php $lang->echo('hwAdd2'); ?></p>
		    </li>
		    <li>
		    	<p class="header"><?php echo $lang->get('whtPrm').'?'; ?></p>
		      	<p id="premium" class="body"><?php echo $lang->get('whtPrm2').' <i class="far fa-smile"></i>'; ?></p>
		    </li>
		    <li>
		    	<p class="header"><?php $lang->echo('crtCrs'); ?></p>
		      	<p id="course" class="body"><?php $lang->echo('crtCrs2'); ?></p>
		    </li>
		    <li>
		    	<p class="header"><?php echo $lang->get('hwCntct').'?'; ?></p>
		      	<p id="contact" class="body"><?php $lang->echo('hwCntct2'); ?></p>
		    </li>
		</ul><!--collapsible-->

	</section><!--settings-box-->
</section><!--settings-->

<script>

$(document).ready(function(){

$('#settings-box').on('click', '.header', function(){
	$(this).next().toggle(ANIMATION);
});

if (window.location.hash) {
	var el = $(window.location.hash);
	setTimeout(function(){
		$('html').animate({ scrollTop: el.offset().top }, ANIMATION);
	}, ANIMATION * 2);
	el.toggle();
}

});

</script>