<script src="<?php echo(PATH_JS.'/jquery.min.js'); ?>"></script>
<script src="<?php echo(PATH_JS.'/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo(PATH_JS.'/Cache.js'); ?>"></script>
<script src="<?php echo(PATH_JS.'/User.js'); ?>"></script>

<section id="main">
<div class="container">
</div><!--container-->
</section><!--main-->

<script>

const ANIMATION = 200;
cache = new Cache();

<?php if (!$premium) { require(DIR_JS.'/blockadblock.js'); ?>

	function adBlockDetected() { cache.get('adBlock'); }

	function adBlockNotDetected() {
		$.post('index.php', {url: 'getSettings'}, function(data){
			user.login(data);
			cache.load(window.location.pathname.slice(17));
		}, dataType = 'json');
	}

	blockAdBlock.setOption({checkOnLoad: true, resetOnEnd: true });
	if (typeof blockAdBlock === 'undefined') { adBlockDetected(); }
	else {
		blockAdBlock.onDetected(adBlockDetected);
		blockAdBlock.onNotDetected(adBlockNotDetected);
	}

<?php } ?>

x = false;
const COURSE = $('consts').attr('course');
const DICIO = $('consts').attr('dicio');
const PATH = $('consts').attr('path');

lightbox = $('#lightbox');
sectionMessages = $('#messages');

function loadMain(url) {
	cache.load(url);
}

function resetLightbox() {
	lightbox.toggle(ANIMATION, function(){
		lightbox.find('h1').text('');
		lightbox.find('.body').text('');
		lightbox.find('#ok').hide();
	});
}

function resetMessage() {
	sectionMessages.toggleClass('hide', ANIMATION, function(){
		sectionMessages.find('p').text('');
	});
}

function showMessage(message) {
	sectionMessages.find('p').text(message);
	sectionMessages.toggleClass('hide', ANIMATION, function(){
		setTimeout(function(){
			resetMessage();
	    }, 4000);
	});
}

function tagWords(words) {
	words = words.split(' ');
	for (var i = 0; i < words.length; i++) {
		words[i] = '<w>'+ words[i] +'</w>';
	}
	return words.join(' ');
}

lightbox.find('#close').on('click', function(){
	resetLightbox();
});

$(document).ready(function(){

	// start section header

	$('#menu a, .menu-btn, .menu-box').on('click', function(){
		$('.menu-box').toggle('slide', ANIMATION);
	});

	$('.dropdown-btn, .dropdown-box a').on('click', function(){
		$('.dropdown-box').toggle('size', ANIMATION);
	});

	$('a[post]').on('click', function(){
		loadMain($(this).attr('post'));
	    return false;
	});

	// end section header

	<?php if ($premium) { ?>

		$.post('index.php', {url: 'getSettings'}, function(data){
			user.login(data);
			loadMain(window.location.pathname.slice(17));
		}, dataType = 'json');

	<?php } ?>

	setTimeout(function(){$('#loading').fadeOut(ANIMATION);}, ANIMATION * 2);

});

</script>

</body>
</html>