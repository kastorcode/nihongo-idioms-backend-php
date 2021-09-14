x = false;
const ANIMATION = 200;
const COURSE = $('consts').attr('course');
const DICIO = $('consts').attr('dicio');
const PATH = $('consts').attr('path');

cache = new Cache();
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

	$.post('index.php',{
		url: 'getSettings'

	}, function(data){
		user.login(data);

		$.post('index.php',{
			url: window.location.pathname.slice(17)

	    }, function(data){
	    	$('#main .container').append(data);
	    	setTimeout(function(){
	    		$('#loading').fadeOut(ANIMATION);
	    	}, 400);
	    });
	}, dataType = 'json');

});