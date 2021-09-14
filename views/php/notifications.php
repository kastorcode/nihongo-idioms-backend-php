<section id="notifications">
	<h1><?php $lang->echo('ntfctns'); ?></h1>

	<div class="bar">
		<button id="clearNotifications" type="button"><i class="fas fa-broom"></i></button>
	</div><!--bar-->

	<section id="notifications-box">
		<!-- <a post="" class="notification btn4"></a> -->
	</section><!--notifications-box-->
</section><!--notifications-->

<script>

$(document).ready(function(){

notificationsBox = $('#notifications-box');

function getNotifications() {
	$.post('index.php',{
		url: 'getNotifications'

		}, function(data) {
			var notices = '';
			for (var i = 0; i < data.my.length; i++) {
				notices += "<a post='my/"+ data.my[i].id +"' class='notification btn4'>"+ data.my[i].title+"</a>";
			}
			for (var i = 0; i < data.notices.length; i++) {
				notices += "<a post='notice/"+ data.notices[i].id +"' class='notification btn4'>["+ data.notices[i].date +"] "+ data.notices[i].title+"</a>";
			}
			notificationsBox.append(notices);

		}, dataType = 'json');
}

$('#clearNotifications').on('click', function(){
	notificationsBox.find('a').toggle(ANIMATION);
	setTimeout(function(){
		notificationsBox.empty();
		user.notifications = 0;
		$.post('index.php',{ url: 'clearNotifications' });
	}, ANIMATION);
});

notificationsBox.on('click', 'a', function(){
	var el = $(this);
	var post = el.attr('post').split('/');

	$.post('index.php',{
		url: 'getNotification',
		type: post[0],
		id: post[1]

		}, function(data) {
			lightbox.find('h1').text(el.text());
			lightbox.find('.body').html("<p>"+ data.content +"</p>");

		}, dataType = 'json');

	lightbox.toggle(ANIMATION);
	el.toggle(ANIMATION);
});

getNotifications();

});

</script>