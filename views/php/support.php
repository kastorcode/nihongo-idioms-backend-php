<section id="chat" class="support">
	<div class="header"></div><!--header-->
	<div class="body">
		<div class="messages">
			<!-- <p class="self"></p> <p class="it"></p> -->
		</div><!--messages-->
		<form id="message">
			<div><input type="text" name="message" minlength="18" maxlength="2000" placeholder="<?php echo $lang->get('msg').'... ['.$lang->get('btwn').' 18 '.$lang->get('and').' 2000 '.$lang->get('chrctrs').'.]'; ?>" /></div>
			<div class="submit"><i class="btn3 fas fa-paper-plane"></i></div>
		</form>
	</div><!--body-->
</section><!--chat-->

<script>

$(document).ready(function(){

divMessages = $('#chat').find('.messages');
formMessage = $('#message');
inputMessage = formMessage.find('input[name=message]');
last = 0;

function appendMessages(data) {
	var messages = '';
	for (var i = 0; i < data.length; i++) {
		switch(data[i].sent) {
			case null:
				messages += "<p class='it'>"+ data[i].message +"</p>";
			break;

			default:
				messages += "<p class='self'>"+ data[i].message +"</p>";
			break;
		}
	}
	divMessages.append(messages);
}

function clearSupport() {
	$.post('index.php',{ url: 'clearSupport' });
}

function getMessages() {
	$.post('index.php',{
		url: 'getMessages',
		last: last

	}, function(data) {
		if (data.length > 0) {
			last = data[data.length - 1].id;
			appendMessages(data);
		}

	}, dataType = 'json');
}

function sendMessage() {
	var message = inputMessage.val();
	if (message.length < 18 || message.length > 2000) return;
	inputMessage.val('');
	appendMessages([{sent: true, message: message}]);

	$.post('index.php',{
		url: 'addMessage',
		message: message
	});
}

formMessage.find('.submit').on('click', function(){ formMessage.submit(); });

formMessage.on('submit', function(e){
	e.preventDefault();
	sendMessage();
});

setInterval(function(){ getMessages(); }, 60000);

getMessages();
clearSupport();

});

</script>