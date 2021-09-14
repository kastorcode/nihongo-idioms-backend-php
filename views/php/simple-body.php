<section id="main">
<div class="container">
</div><!--container-->
</section><!--main-->

<script src="<?php echo(PATH_JS.'/jquery.min.js') ?>"></script>
<script src="<?php echo(PATH_JS.'/jquery-ui.min.js') ?>"></script>

<script>

x = false;
const ANIMATION = 200;
const PATH = $('consts').attr('path');


function loadMain(url) {
	$.post('index.php', { url: url }, function(data){
		$('#main').find('.container').empty().append(data);
	}, dataType = 'html');
}

$(document).ready(function(){

loadMain(window.location.pathname.slice(17));

});

</script>

<?php if ($logged) { ?>

<script>
$(document).ready(function(){

	$('#header').on('click', '#close', function(e){
		e.preventDefault();
		window.open('', '_self').close();
	});

});
</script>

<?php } ?>

</body>
</html>