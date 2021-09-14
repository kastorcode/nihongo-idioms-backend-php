<div class="cards">
	<div class="course">
		<label><?php $lang->echo('crs'); ?></label>
		<select name="course">
			<!-- <option value=""></option> -->
		</select>
	</div><!--course-->

		<div class="card" post="vocabulary">
			<div class="card-header">
				<p><?php $lang->echo('phrssToRvw'); ?></p>
			</div><!--card-header-->
			<div class="card-body">
				<p id="vocabulary"></p>
			</div><!--card-body-->
		</div><!--card-->

		<div class="card" post="notifications">
			<div class="card-header">
				<p><?php $lang->echo('ntfctns'); ?></p>
			</div><!--card-header-->
			<div class="card-body">
				<p id="notifications"></p>
			</div><!--card-body-->
		</div><!--card-->

		<!-- <div class="card" post="support">
			<div class="card-header">
				<p>Suporte</p>
			</div>!-card-header--
			<div class="card-body">
				<p id="support"></p>
			</div>!--card-body--
		</div>!--card-->
</div><!--cards-->

<script>

$(document).ready(function(){

sectionCards = $('.cards');

$('#vocabulary').text(user.revisions);
$('#notifications').text(user.notifications);

$('select').change(function(){
	var course = $(this).val();

	$.post('index.php',{
		url: 'changeCourse',
		course: course

    }, function(data){
    	if (data.success) {
    		window.location.reload();
    	}

    }, dataType = 'json');
});

sectionCards.on('click', '.card', function(){
	loadMain($(this).attr('post'));
});

$.post('index.php',{
	url: 'getMyCourses'

}, function(data) {
	var courses = '';
	for (var i = 0; i < data.length; i++) {
		courses += '<option value="'+ data[i][0] +'">'+ data[i][1] +'</option>';
	}
	sectionCards.find('.course').find('[name=course]').append(courses);

}, dataType = 'json');

});

</script>