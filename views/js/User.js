var user = {

	applyTheme: function() {
		if (this.theme)
			$('head').append("<link value='dark' href='"+ PATH +"/views/css/dark.css' rel='stylesheet' />");
		else
			$('head').find("link[value='dark']").remove();
	},

	decreaseRevisions: function() {
		if (this.revisions > 0) this.revisions--;
	},

	incrementRevisions: function() {
		this.revisions++;
		vocabularyMenu.find('.title').find('span').text(user.revisions);
	},

	login: function(data) {
		this.name = data.name;
    	this.avatar = data.avatar;
    	this.theme = parseInt(data.theme);
    	this.auto = parseInt(data.auto);
    	this.repro = parseInt(data.repro);
    	this.notifications = parseInt(data.notifications);
    	this.revisions = parseInt(data.revisions);

    	$('#menu').find('h1').append(data.name);
    	this.applyTheme();
	},
};