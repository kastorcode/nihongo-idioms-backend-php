var Cache = function() {

	this.main = $('#main').find('.container');
	this.forum = null;
	this.home = null;
	this.modules = null;
	this.notifications = null;
	this.settings = null;
	this.shadowing = null;
	this.vocabulary = null;

	this.get = function(url) {
		var Cache = this;

		$.post('index.php', { url: url }, function(data){
			Cache.main.empty().append(data);
			if (Cache[url] === null) Cache[url] = data;
		}, dataType = 'html');
	};

	this.load = function(url) {
		if (this[url]) { this.main.empty().append(this[url]); }
		else { this.get(url); }
	};
}