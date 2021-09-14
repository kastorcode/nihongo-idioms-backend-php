var Phrase = function(id, original, translation){
	this.id = id;
	this.original = original;
	this.translation = translation;

	this.playAudio = function() {
		repro = user.repro ? false : true;

		if (user.auto) {
			audio.loop = true;
			audio.play();
		}

		audio.onloadeddata = function(){
			setTimeout(function(){
				audio.loop = false;
				repro = true;
			}, ((audio.duration * user.repro) * 1000));
		};
	};

	this.show = function() {
		tagAudio.attr('src', PATH +'/files/vocabulary/'+ COURSE +'/'+ id +'.mp3');
		phraseOriginal.html(this.original);
		phraseTranslation.text(this.translation);
		this.playAudio();
	};
}