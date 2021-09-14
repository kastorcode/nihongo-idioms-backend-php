<section id="settings">
	<h1><?php $lang->echo('stngs'); ?></h1>

	<section id="settings-box">
		<img id="avatar" src="" />
		<a class="btn4" target="_blank" href="<?php echo(PATH.'/tutorial') ?>">Tutorial</a>
		<a post="premium" class="btn4" href="<?php echo(PATH.'/premium') ?>">Premium</a>
		<form id="name">
			<input required type="text" name="name" value="" minlength="4" maxlength="20" />
			<input class="btn1" type="submit" name="submit" value="<?php $lang->echo('chng'); ?>" />
		</form>
		<div class="checkbox">
			<label for="theme"><?php $lang->echo('drkThm'); ?></label>
			<input type="checkbox" id="theme" value="" />
		</div><!--checkbox-->
		<!-- <div class="checkbox">
			<label for="private">Conta privada</label>
			<input type="checkbox" id="private" value="<?php echo($settings['private']) ?>" />
		</div>!--checkbox-->
		<div class="checkbox">
			<label for="auto"><?php $lang->echo('atmtcPlybck'); ?></label>
			<input type="checkbox" id="auto" value="" />
		</div><!--checkbox-->
		<div class="options">
			<label><?php $lang->echo('mnmRprdctn'); ?></label>
			<select id="repro">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
		</div>
		<div class="options">
			<label><?php $lang->echo('myGndr'); ?></label>
			<select id="gender">
				<option <?php if ($settings['gender'] == 'M') { echo('selected'); } ?> value="M"><?php $lang->echo('ml'); ?></option>
				<option <?php if ($settings['gender'] == 'F') { echo('selected'); } ?> value="F"><?php $lang->echo('fml'); ?></option>
			</select>
		</div>
		<div class="options">
			<label><?php echo $lang->get('add').' '.lcfirst($lang->get('crs')); ?></label>
			<select id="addCourse">
				<!-- <option value=""></option> -->
			</select>
		</div>
		<?php if ($premium) { ?>
			<div class="checkbox">
				<label for="removeAds"><?php $lang->echo('rmveAds'); ?></label>
				<input type="checkbox" id="removeAds" value="<?php echo($user->removeAds()); ?>" />
			</div><!--checkbox-->
		<?php } ?>
	</section><!--settings-box-->
</section><!--settings-->

<script>

$(document).ready(function(){

$('#avatar').attr('src', 'https://' + user.avatar);
$('#name').find('input[name=name]').attr('value', user.name);
$("#theme").val(user.theme);
$("#auto").val(user.auto);
$('select').find('option[value='+ user.repro +']').attr('selected', '');

$('#settings-box').on('click', '[post]', function(e){
	e.preventDefault();
	loadMain($(this).attr('post'));
});

$('#name').submit(function(e){
	e.preventDefault();
	var name = $(this).find('input[name=name]').val();

	$.post('index.php',{
		url: 'changeName',
		name: name,

	}, function(data) {
		if (data.success) {
			user.name = name;
			$('#menu h1').text(name);
			showMessage(data.message);
		}

	}, dataType = 'json');
});

$('.checkbox').on('click', function(){
	var el = $(this).find('input');
	var url = el.attr('id');
	var bool = parseInt(el.attr('value'));
	if (bool == 0) bool = 1;
	else bool = 0;

	el.val(bool);
	user[url] = bool;

	switch(url) {
		case 'theme': user.applyTheme();
		break;
	}

	$.post('index.php',{
		url: url,
		bool: bool
    }, function(){
    	switch(url) {
			case 'removeAds': window.location.reload();
			break;
		}
    });
});

$('select').change(function(){
	var el = $(this);
	var url = el.attr('id');
	var value = el.val();

	switch(url) {
		case 'repro': user.repro = parseInt(value);
		break;
	}

	$.post('index.php', {
		url: url,
		value: value
    }, function(data){
    	if (data.message != undefined) showMessage(data.message);

    }, dataType = 'json');
});

$.post('index.php',{
	url: 'getCourses'

}, function(data){
	var courses = '';
	for (var i = 0; i < data.length; i++) {
		courses += '<option value="'+ data[i][0] +'">'+ data[i][1] +'</option>';
	}
	$('#addCourse').append(courses);

}, dataType = 'json');

});

</script>