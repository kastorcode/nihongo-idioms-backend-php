<div class="my-phrases">
	<h1><?php echo $lang->echo('ttl').': <span>'.$total; ?></span></h1>

	<form id="searchMyPhrases" class="search">
		<input required type="text" name="text" maxlength="100" />
		<input type="submit" name="search" value="<?php $lang->echo('srchFr'); ?>" />
	</form>

	<div class="bar">
		<button id="learnedPhrases" type="button"><i class="fab fa-leanpub"></i></button>
	</div><!--bar-->

	<div class="box">

		<?php foreach ($phrases as $value) { ?>
		<div class="phrase-box">
			<div class="text"><?php echo($value['phrase']); ?></div>
			<div class="last"><?php echo($value['last_revision']); ?></div>
			<div class="revisions"><?php $lang->echo('rvsns'); ?>: <?php echo($value['revisions']); ?></div>
			<i class="fas fa-trash" id="<?php echo($value['phrase_id']); ?>"></i>
		</div><!--phrase-box-->
		<?php } ?>

	</div><!--box-->
</div><!--my-phrases-->

<script>

$(document).ready(function(){

sectionMyPhrases = $('.my-phrases');
formSearchMyPhrases = $('#searchMyPhrases');
myPhrasesBox = sectionMyPhrases.find('.box');

// start div my-phrases

function getLearnedPhrases() {
	var last = myPhrasesBox.find('.phrase-box:last-child').find('.fas').attr('id');

	$.post('index.php',{
		url: 'getLearnedPhrases',
		last: last

	}, function(data){
		var phrases = '';
		for (var i = 0; i < data.length; i++) {
			phrases += "<div class='phrase-box'><div class='text'>"+ data[i]['phrase'] +"</div><div class='last'>"+ data[i]['last_revision'] +"</div><div class='revisions'>Revisões: "+ data[i]['revisions'] +"</div><i class='fas fa-trash' id='"+ data[i]['phrase_id'] +"'></i></div>";
		}
		myPhrasesBox.append(phrases);

	}, dataType = 'json');
}

function searchMyPhrases() {
	var text = formSearchMyPhrases.find('[name=text]').val();
	var last = myPhrasesBox.find('.phrase-box:last-child').find('.fas').attr('id');

	$.post('index.php',{
		url: 'searchMyPhrases',
		text: text,
		last: last

	}, function(data) {
		sectionMyPhrases.find('span').text('?');
		var phrases = '';
		for (var i = 0; i < data.length; i++) {
			phrases += "<div class='phrase-box'><div class='text'>"+ data[i]['phrase'] +"</div><div class='last'>"+ data[i]['last_revision'] +"</div><div class='revisions'>Revisões: "+ data[i]['revisions'] +"</div><i class='fas fa-trash' id='"+ data[i]['id'] +"'></i></div>";
		}
		myPhrasesBox.append(phrases);

	}, dataType = 'json');
}

$('#searchMyPhrases').submit(function(e){
	e.preventDefault();
	scrollEnd[0] = searchMyPhrases;
	myPhrasesBox.empty();
	searchMyPhrases();
});

$('#learnedPhrases').on('click', function(){
	scrollEnd[0] = getLearnedPhrases;
	myPhrasesBox.toggle(ANIMATION, function(){ $(this).empty(); });

	$.post('index.php',{
		url: 'getTotalLearned'
	}, function(data){
		sectionMyPhrases.find('span').text(data.total);
	}, dataType = 'json');

	setTimeout(function(){
		getLearnedPhrases();
		myPhrasesBox.toggle(ANIMATION);
	}, ANIMATION * 2);
});

myPhrasesBox.on('click', '.fa-trash', function(){
	var el = $(this);

	var parent = el.parent();
	$(parent).toggle('explode', ANIMATION, function(){
		$(parent).remove();
	});

	var id = el.attr('id');

	$.post('index.php',{
		url: 'deletePhrase',
		id: id
    });
});

// end div my-phrases

$(window).scroll(function(){
	var el = $(this);
	if ((el.scrollTop() + el.height()) > ($(document).height() - 1)) {
		scrollEnd[0]();
	}
});

scrollEnd = [searchMyPhrases];

});

</script>