<section id="settings">
	<h1><?php $lang->echo('adBlckDtctd'); ?> <i class="fas fa-sad-tear"></i></h1>

	<section id="settings-box">
		<p><?php $lang->echo('usngAdBlckr'); ?></p>
		<p><?php $lang->echo('ptWhtlst'); ?></p>
		<p><?php $lang->echo('frVrsn'); ?></p>
		<p><?php $lang->echo('evsvAds'); ?></p>
		<p><?php $lang->echo('upgrdSprt'); ?></p>
		<p><?php $lang->echo('thnksDay'); ?></p>

		<a post="premium" class="btn3" href="<?php echo(PATH.'/premium') ?>">Premium</a>
		<a id="reload" class="btn3"><?php $lang->echo('rld'); ?></a>
	</section><!--settings-box-->
</section><!--settings-->

<script>

$(document).ready(function(){

$('#header').remove();

var sectionSettingsBox = $('#settings-box');

sectionSettingsBox.on('click', '[post]', function(e){
	e.preventDefault();
	loadMain($(this).attr('post'));
});

sectionSettingsBox.on('click', '#reload', function(){
	window.location.reload();
});

});

</script>