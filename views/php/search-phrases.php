<div class="phrases">

	<form id="searchPhrases" class="search">
		<input required type="text" name="text" maxlength="100" />
		<input type="submit" name="search" value="<?php $lang->echo('srchFr'); ?>" />
	</form>

	<div class="box">
	</div><!--box-->

</div><!--phrases-->

<script>

$(document).ready(function(){

// start div phrases

function addExistingPhrase() {
	$('.phrase-box').on('click', function(){
		var el = $(this);
		var id = el.attr('id');
		el.toggle('size', 200);

		$.post('index.php',{
			url: 'addExistingPhrase',
			id: id,
		});
	});
}

function searchPhrases() {
	var text = $('input[name=text]').val();
	var last = $('div.phrase-box:last-child').attr('id');

	$.post('index.php',{
		url: 'searchPhrases',
		text: text,
		last: last

	}, function(data) {
		for (var i = 0; i < data.length; i++) {
			$('div.box').append("<div class='phrase-box' id='"+ data[i]['id'] +"'><p class='original'>"+ data[i]['phrase'] +"</p><p class='translation'>"+ data[i]['translation'] +"</p></div>");
		}
		addExistingPhrase();

	}, dataType = 'json');
}

$('form#searchPhrases').submit(function(e){
	e.preventDefault();
	$('div.box').empty();
	searchPhrases();
});

$(window).scroll(function(){
	var el = $(this);
	if (el.scrollTop() + el.height() == $(document).height()) {
		searchPhrases();
	}
});

// end div phrases

});

</script>