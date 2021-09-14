<div class="toggle-1">
<section id="forum-menu">
	<h1><?php $lang->echo('frm'); ?></h1>
	<form class="search">
		<input required type="text" name="text">
		<input type="submit" name="submit" value="<?php $lang->echo('srchFr'); ?>">
	</form>
	<div class="bar">
		<button id="addQuestion" type="button" title="<?php $lang->echo('adQstn'); ?>"><i class="fas fa-plus-circle"></i></button>
		<button id="myQuestions" type="button" title="<?php $lang->echo('myQstns'); ?>"><i class="fas fa-question-circle"></i></button>
		<button post="2" type="button" title="<?php $lang->echo('sgstns'); ?>"><i class="fas fa-clipboard-list"></i></button>
		<button post="1" type="button" title="<?php $lang->echo('bgs'); ?>"><i class="fas fa-bug"></i></button>
	</div><!--bar-->
	<!-- <div post="" class="forum-box">
		<p class="question"></p>
		<div class="answers-box">
			<i class="fas fa-comment-alt"></i>
			<p class="answers"></p>
		</div> answers-box
	</div> forum-box
	<div class="pagination">
		<div class="pagination-box">
			<a>1</a>
			<span>2</span>
		</div> pagination-box
	</div> pagination-->
</section><!--forum-menu-->
</div><!--toggle-1-->

<div class="toggle-2" style="display: none;">
<section id="forum-question">
	<div post="" class="question">
		<div class="header">
			<div><img src="" /></div>
			<div>
				<h1></h1>
				<span></span>
				<a user=""></a>
			</div>
		</div><!--header-->
		<div class="body"></div><!--body-->
	</div><!--question-->

	<div class="reply">
		<div class="reply-box">
			<img src="" />
			<form id="reply" class="add-phrase">
				<div class="form-box">
					<textarea required minlength="20" maxlength="2000" name="reply" placeholder="<?php echo $lang->get('lvRply').'...&#10'.$lang->get('sprtdTgs').': <audio> <iframe> <img> <video>&#10'.$lang->get('szBtwn').' 20 '.$lang->get('and').' 2000 '.$lang->get('chrctrs').'.'; ?>"></textarea>
				</div><!--form-box-->
				<div class="form-box">
					<input type="submit" name="submit" value="<?php $lang->echo('pblsh'); ?>" />
				</div><!--form-box-->
			</form>
		</div><!--reply-box-->
	</div><!--reply-->

	<div class="answers">
		<!-- <div class="answer">
			<div class="header">
				<img src="" />
				<div>
					<a></a>
					<span></span>
				</div>
			</div> header
			<div class="body">
				<p></p>
			</div> body
		</div> answer -->
	</div><!--answers-->
</section><!--forum-question-->
<div id="come-back"><i class="fas fa-long-arrow-alt-left"></i></div>
</div><!--toggle-2-->

<script>

$(document).ready(function(){

forumMenu = $('#forum-menu');
sectionBar = forumMenu.find('.bar');
forumQuestion = $('#forum-question');
divQuestion = forumQuestion.find('.question');
toggle1 = $('.toggle-1');
toggle2 = $('.toggle-2');
last = 0;

function appendQuestions(data) {
	var questions = '';
	for (var i = 0; i < data.length; i++) {
		questions += "<div post='"+ data[i].id +"' class='forum-box'><p class='question'>"+ data[i].question +"</p><div class='answers-box'><i class='fas fa-comment-alt'></i><p class='answers'>"+ data[i].replies +"</p></div></div>";
	}
	forumMenu.append(questions);
}

function getMyQuestions() {
	$.post('index.php',{
		url: 'getMyQuestions',
		last: last

	}, function(data) {
		if (data.length > 0) {
			last = data[0].id;
			appendQuestions(data);
		}

	}, dataType = 'json');
}

function getQuestion(id, question) {
	$.post('index.php',{
		url: 'getQuestion',
		id: id

		}, function(data) {
			var header = divQuestion.find('.header');
			header.find('img').attr('src', 'https://'+ data.avatar);
			header.find('h1').text(question);
			header.find('span').text(data.date);
			header.find('a').attr('user', data.user_id).text(data.name);
			divQuestion.find('.body').html(data.content);
			forumQuestion.find('.reply-box').find('img').attr('src', 'https://'+ user.avatar);

		}, dataType = 'json');
}

function getReplies() {
	var id = divQuestion.attr('post');
	var last = forumQuestion.find('.answer:last-child').attr('id');

	$.post('index.php',{
		url: 'getReplies',
		id: id,
		last: last

		}, function(data) {
			var answers = '';
			for (var i = 0; i < data.length; i++) {
				answers += "<div id='"+ data[i].id +"' class='answer'><div class='header'><img src='https://"+ data[i].avatar +"' user='"+ data[i].user_id +"' /><div><a user='"+ data[i].user_id +"'>"+ data[i].name +"</a><span>"+ data[i].date +"</span></div></div><div class='body'><p>"+ data[i].reply +"</p></div></div>";
			}
			forumQuestion.find('.answers').append(answers);

		}, dataType = 'json');
}

function getQuestions() {
	$.post('index.php',{
		url: 'getQuestions',
		last: last

	}, function(data) {
		if (data.length > 0) {
			last = data[0].id;
			appendQuestions(data);
		}

	}, dataType = 'json');
}

function searchQuestions() {
	var text = forumMenu.find('.search').find('[name=text]').val();
	var last = forumMenu.find('.forum-box:last-child').attr('post');

	$.post('index.php',{
		url: 'searchQuestions',
		text: text,
		last: last

	}, function(data) { appendQuestions(data);
	}, dataType = 'json');
}

forumMenu.on('click', '.forum-box', function(){
	scrollEnd.unshift(getReplies);
	var el = $(this);
	var id = el.attr('post');
	divQuestion.attr('post', id);
	var question = el.find('.question').text();
	getQuestion(id, question);
	toggle1.toggle(ANIMATION, function(){
		toggle2.toggle(ANIMATION);
		getReplies();
	});
});

forumMenu.find('.search').submit(function(e){
	e.preventDefault();
	scrollEnd.unshift(searchQuestions);
	forumMenu.find('.forum-box').remove();
	searchQuestions();
});

$('#addQuestion').on('click', function(){
	lightbox.find('h1').text($(this).attr('title'));
	lightbox.find('.body').html("<div><img src='https://"+ user.avatar +"'/></div><div><input required type='text' name='question' minlength='18' maxlength='132' placeholder='Pergunta [Entre 18 e 132 caracteres.]'/></div><div><textarea required minlength='18' maxlength='2000' name='content' placeholder='Tags suportadas: <audio> <iframe> <img> <video>&#10Tamanho entre 18 e 2000 caracteres.'></textarea></div>");
	lightbox.find('#ok').show(function(){
		lightbox.toggle(ANIMATION);
	});
});

$('#myQuestions').on('click', function(){
	last = 0, scrollEnd[0] = getMyQuestions;
	var title = $(this).attr('title');
	forumMenu.find('.forum-box').toggle(ANIMATION , function() { $(this).remove(); });
	setTimeout(function(){
		forumMenu.find('h1').text(title);
		getMyQuestions();
	}, ANIMATION);
});

sectionBar.on('click', '[post]', function(){
	scrollEnd.unshift(getReplies);
	var el = $(this);
	var id = el.attr('post');
	divQuestion.attr('post', id);
	getQuestion(id, el.attr('title'));
	toggle1.toggle(ANIMATION, function(){
		toggle2.toggle(ANIMATION);
		getReplies();
	});
});

$('#ok').on('click', function(){
	var body = lightbox.find('.body');
	var question = body.find('[name=question]').val();
	var content = body.find('[name=content]').val();

	$.post('index.php',{
		url: 'addQuestion',
		question: question,
		content: content

	}, function(data) {
		if (data.success) resetLightbox();
	}, dataType = 'json');
});

$('#reply').submit(function(e){
	e.preventDefault();
	var id = divQuestion.attr('post');
	var textarea = $('#reply').find('textarea');
	var reply = textarea.val();

	$.post('index.php',{
		url: 'addReply',
		id: id,
		reply: reply

		}, function(data) {
			if (data.success) textarea.val('');

		}, dataType = 'json');
});

$('#come-back').on('click', function(){
	toggle2.toggle(ANIMATION , function(){
		toggle1.toggle(ANIMATION, function(){
			scrollEnd.shift();
			forumQuestion.find('.answers').empty();
		});
	});
});

$(window).off('scroll').scroll(function(){
	var el = $(this);
	if ((el.scrollTop() + el.height()) > ($(document).height() - 1)) {
		scrollEnd[0]();
	}
});

scrollEnd = [getQuestions];
scrollEnd[0]();

});

</script>