<div class="toggle-1">
<div class="modules">
	<!-- <a post=""></a> -->
</div><!--modules-->
</div><!--toggle-1-->

<div class="toggle-2" style="display: none;">
<div class="module">
	<h1></h1>
	<div class="explanation"></div><!--explanation-->

	<div class="phrases">
		<!-- <p class="add">ADICIONAR TUDO</p>
		<div class="phrase-box">
			<p class="original"></p>
			<p class="translation"></p>
		</div> -->
	</div><!--phrases-->
</div><!--module-->
</div><!--toggle-2-->

<script>

$(document).ready(function(){

sectionModules = $('.modules');
sectionModule = $('.module');
toggle1 = $('.toggle-1');
toggle2 = $('.toggle-2');

sectionModule.on('click', '.phrase-box', function(){
	var el = $(this);
	var phrase = el.find('.original').text();
	var translation = el.find('.translation').text();

	$.post('index.php',{
		url: 'addPhrase',
		phrase: phrase,
		translation: translation
	});

	el.toggle(ANIMATION);
});

sectionModules.on('click', 'a', function(){
	var id = $(this).attr('post');
	toggle1.toggle(ANIMATION);

	$.post('index.php',{
		url: 'getModule',
		id: id

		}, function(data) {
			sectionModule.find('h1').text("Módulo "+ data.order +" - "+ data.title);
			sectionModule.find('.explanation').html(data.explanation);
			var phrases = '';
			for (var i = 0; i < data.phrases.length; i++) {
				phrases += "<div class='phrase-box'><p class='original'>"+ data.phrases[i].phrase +"</p><p class='translation'>"+ data.phrases[i].translation +"</p></div>";
			}
			sectionModule.find('.phrases').append(phrases);
			toggle2.toggle(ANIMATION);

	}, dataType = 'json');
});

$.post('index.php',{
	url: 'getModules'

	}, function(data) {
		var modules = '';
		for (var i = 0; i < data.length; i++) {
			modules += "<a post='"+ data[i].id +"'>Módulo "+ (i+1) +" - "+ data[i].title +"</a>";
		}
		sectionModules.append(modules);

	}, dataType = 'json');

});

</script>