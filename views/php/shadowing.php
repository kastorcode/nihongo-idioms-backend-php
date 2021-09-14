<div class="toggle-1">
<section id="shadowing-menu">
	<div class="basic">
		<h1><?php $lang->echo('bsc'); ?></h1>
		<!-- <a post=""></a> -->
	</div><!--basic-->
	<div class="intermediary">
		<h1><?php $lang->echo('intrmdry'); ?></h1>
	</div><!--intermediary-->
	<div class="advanced">
		<h1><?php $lang->echo('advncd'); ?></h1>
	</div><!--advanced-->
</section><!--shadowing-menu-->
</div><!--toggle-1-->

<div class="toggle-2" style="display: none;">
<section id="shadowing-text">
	<h1></h1>
	<div class="shadowing-options">
		<i id="audio" class="fas fa-volume-up"></i>
		<i id="captions" class="fas fa-closed-captioning"></i>
		<audio src=""></audio>
	</div><!--shadowing-options-->
	<div class="text">
		<!-- <p class="original"></p>
		<p class="translation"></p> -->
	</div><!--text-->
</section><!--shadowing-text-->
</div><!--toggle-2-->

<script>

$(document).ready(function(){

shadowingMenu = $('#shadowing-menu');
shadowingText = $('#shadowing-text');
audio = $('audio')[0];
translation = null;
toggle1 = $('.toggle-1');
toggle2 = $('.toggle-2');

function playAudio() {
	if (audio.paused) audio.play();
	else audio.pause();
}

function toggleCaptions() {
	switch (translation.css('visibility')) {
		case 'visible': translation.css('visibility', 'hidden'); break;
		default: translation.css('visibility', 'visible'); break;
	}
}

shadowingText.on('click', '#captions', function(){
	toggleCaptions();
});

shadowingMenu.on('click', 'a', function(){
	var el = $(this);
	var id = el.attr('post');
	toggle1.toggle(ANIMATION);

	$.post('index.php',{
		url: 'getShadowing',
		id: id

		}, function(data) {
			shadowingText.find('h1').text(el.text());
			shadowingText.find('audio').attr('src', PATH +'/files/shadowing/'+ COURSE +'/'+ id + '.mp3');
			data = data.text.split('\\');
			var text = '';
			for (var i = 0; i < data.length; i += 2) {
				text += "<p class='original'>"+ tagWords(data[i]) +"</p><p class='translation'>"+ data[i+1] +"</p>";
			}
			shadowingText.find('.text').append(text);
			translation = shadowingText.find('.translation');

		}, dataType = 'json');

	toggle2.toggle(ANIMATION);
});

$('#audio').on('click', function(){
	playAudio();
});

shadowingText.find('.text').on('click', 'w', function(){
	lightbox.find('.body').html("<iframe src='"+ DICIO.replace('{word}', $(this).text()) +"'></iframe>");
	lightbox.toggle(ANIMATION);
});

$(document).keypress(function(e){
	switch(e.which) {
		case 32: case 112: playAudio();
		break;
		case 13: case 99: toggleCaptions();
		break;
	}
});

$.post('index.php',{
	url: 'getTexts'

	}, function(data) {
		var basic = shadowingMenu.find('.basic');
		var intermediary = shadowingMenu.find('.intermediary');
		var advanced = shadowingMenu.find('.advanced');
		for (var i = 0; i < data.length; i++) {
			switch (data[i].level) {
				case '0': basic.append("<a post='"+ data[i].id +"'>"+ data[i].title +"</a>"); break;
				case '1': intermediary.append("<a post='"+ data[i].id +"'>"+ data[i].title +"</a>"); break;
				case '2': advanced.append("<a post='"+ data[i].id +"'>"+ data[i].title +"</a>"); break;
			}
		}

	}, dataType = 'json');

});

</script>