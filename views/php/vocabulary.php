<div class="toggle-1">
<div class="vocabulary-menu">
	<span class="title">
		<?php $lang->echo('phrssToRvw'); ?>
		<span class="value">0</span>
	</span>
	<i class="fas fa-caret-down"></i>
	<nav class="vocabulary-box">
		<a name="0"><?php $lang->echo('tdy'); ?></a>
		<a name="1"><?php $lang->echo('ystrdy'); ?></a>
		<a name="2"><?php $lang->echo('tmrw'); ?></a>
		<a name="3"><?php $lang->echo('rndm'); ?></a>
	</nav><!--vocabulary-box-->
</div><!--vocabulary-menu-->

<div class="phrase-manager">
	<button class="add" type="button"><?php $lang->echo('adPhrs'); ?></button>
	<button class="show" type="button" name="myPhrases"><?php $lang->echo('myPhrs'); ?></button>
	<button type="button" name="renderSearchPhrases"><?php $lang->echo('srchPhrs'); ?></button>

	<form class="add-phrase">
		<div class="form-box">
			<input required type="text" name="phrase" minlength="14" maxlength="100" placeholder="<?php $lang->echo('phrs'); ?>" />
		</div><!--form-box-->
		<div class="form-box">
			<input required type="text" name="translation" minlength="14" maxlength="100" placeholder="<?php $lang->echo('trnsltn'); ?>" />
		</div><!--form-box-->
		<div class="form-box">
			<input type="submit" name="add-phrase" value="<?php $lang->echo('add'); ?>" />
		</div><!--form-box-->
	</form>
</div><!--phrase-manager-->
</div><!--toggle-1-->

<div class="toggle-2" style="display: none">
<div class="phrase">
	<audio src=""></audio>
	<i id="audio" class="fas fa-volume-up"></i>
	<p class="original"></p>
	<p class="translation"></p>
	<div class="buttons">
		<button class="show" type="button"><?php $lang->echo('shwAnswr'); ?></button>
		<div class="choices">
			<button class="again" type="button" name="0"><?php $lang->echo('rpt'); ?></button>
			<button class="tomorrow" type="button" name="1"><?php $lang->echo('tmrw'); ?></button>
			<button class="ok" type="button" name="2"><?php $lang->echo('ok'); ?></button>
		</div><!--choices-->
	</div><!--buttons-->
</div><!--phrase-->
</div><!--toggle-2-->

<script src="<?php echo(PATH_JS.'/Phrase.js') ?>"></script>

<script>

$(document).ready(function(){

vocabularyMenu = $('.vocabulary-menu');
vocabularyBox = vocabularyMenu.find('.vocabulary-box');
phraseManager = $('.phrase-manager');
toggle1 = $('.toggle-1');
toggle2 = $('.toggle-2');

function toggleVocabularyBox() {
	vocabularyBox.toggle('size', ANIMATION);
}

// start div vocabulary-menu

vocabularyMenu.find('.title').find('span').text(user.revisions);

vocabularyMenu.on('click', '.fas', function(){
	toggleVocabularyBox();
});

vocabularyBox.on('click', 'a', function(){
	toggleVocabularyBox();
});

// end div vocabulary-menu

// start div vocabulary-menu

vocabularyMenu.on('click', 'span', function(){
	if (user.revisions > 0) loadPhrase();
	else toggleVocabularyBox();
});

// end div vocabulary-menu

// start div phrase-manager

phraseManager.on('click', '.add', function(){
	phraseManager.find('.add-phrase').toggle('blind', ANIMATION);
});

phraseManager.find('.add-phrase').on('click', '[name=add-phrase]', function(e){
	e.preventDefault();
	var el = $(this);
	el.attr('disabled', 1);
	var elFormAddPhrase = phraseManager.find('.add-phrase');
	var elPhrase = elFormAddPhrase.find('[name=phrase]');
	var elTranslation = elFormAddPhrase.find('[name=translation]');

	$.post('index.php',{
		url: 'addPhrase',
		phrase: elPhrase.val(),
		translation: elTranslation.val()

	}, function(data) {
		if (data.success) {
			elPhrase.val('');
			elTranslation.val('');
			user.incrementRevisions();
		}

	}, dataType = 'json').always(function(){
		el.removeAttr('disabled');
	});
});

$('.phrase-manager [name=myPhrases], .phrase-manager [name=renderSearchPhrases]').on('click', function(){
	var url = $(this).attr('name');
	toggle1.toggle('fade', ANIMATION, function(){
		$.post('index.php',{
			url: url

		}, function(data) {
			var el = $('#main .container');
			el.empty();
	    	el.append(data);
		});
	});
});

// end div phrase-manager

// start div phrase

sectionPhrase = $('.phrase');
tagAudio = sectionPhrase.find('audio');
audio = $('audio')[0];
repro = false;
phraseOriginal = sectionPhrase.find('.original');
phraseTranslation = sectionPhrase.find('.translation');
phraseChoices = sectionPhrase.find('.choices');
phraseShow = sectionPhrase.find('.show');
phrases = [];

function getPhrases() {
	if (phrases.length < 2) {
		$.post('index.php',{
			url: 'getPhrases',

		}, function(data) {
			for (var i = 0; i < data.length; i++) {
				phrases.push(new Phrase(data[i]['phrase_id'], tagWords(data[i]['phrase']), data[i]['translation']));
			}

		}, dataType = 'json');
	}
}

function togglePhrase() {
	toggle1.toggle('scale', ANIMATION, function(){
		toggle2.toggle('scale', ANIMATION);
		showPhrase();
	});
}

function loadPhrase() {
	training = false;
	getPhrases();
	togglePhrase();
}

function playAudio() {
	audio.paused ? audio.play() : audio.pause();
}

function resetChoices() {
	phraseTranslation.css('visibility', 'hidden');
	phraseChoices.toggle();
	phraseShow.toggle();
}

function showPhrase() {
	if (phrases[0] == undefined) { loadMain('vocabulary'); }
	else { phrases[0].show(); }
}

$('#audio').on('click', function(){
	playAudio();
});

sectionPhrase.find('.original').on('click', 'w', function(){
	lightbox.find('.body').html("<iframe src='"+ DICIO.replace('{word}', $(this).text()) +"'></iframe>");
	lightbox.toggle(ANIMATION);
});

phraseShow.on('click', function(){
	if (repro) {
		$(this).toggle();
		phraseChoices.toggle();
		phraseTranslation.css('visibility', 'visible');
	}
});

phraseChoices.on('click', 'button', function(){
	var choice = $(this).attr('name');

	if (!training) {
		if (choice == 0) { phrases.push(phrases.shift()); }
		else {
			$.post('index.php',{
				url: 'phrase',
				choice: choice,
				id: phrases[0].id

			}, dataType = 'json');
			phrases.shift();
			user.decreaseRevisions();
		}
		resetChoices();
		showPhrase();
		getPhrases();

	} else {
		if (choice == 0) { phrases.push(phrases.shift()); }
		else { phrases.shift(); }
		resetChoices();
		showPhrase();
		getTraining();
	}
});

document.querySelector('body').addEventListener('keydown', function(e){
	switch(e.keyCode) {
		case 32: case 80: playAudio();
		break;
		case 38: case 87: phraseShow.trigger('click');
		break;
		case 37: case 65: if (repro) phraseChoices.find('.again').trigger('click');
		break;
		case 40: case 83: if (repro) phraseChoices.find('.tomorrow').trigger('click');
		break;
		case 39: case 68: if (repro) phraseChoices.find('.ok').trigger('click');
		break;
	}
});

day = 0;
training = false;

function getTraining() {
	if (phrases.length < 2) {
		if (phrases[0] == undefined) var id = 0;
		else var id = phrases[0].id;

		$.post('index.php',{
			url: 'train',
			id: id,
			day: day

		}, function(data) {
			for (var i = 0; i < data.length; i++) {
				phrases.push(new Phrase(data[i]['phrase_id'], tagWords(data[i]['phrase']), data[i]['translation']));
			}

		}, dataType = 'json');
	}
}

vocabularyBox.on('click', 'a', function(){
	day = $(this).attr('name');
	training = true;
	phrases = [];
	getTraining();
	togglePhrase();
});

// end div phrase

});

</script>