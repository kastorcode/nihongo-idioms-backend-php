var lightbox = {

	construct: function(){
		this.el = $('#lightbox');
	},

	reset: function() {
		this.el.find('h1').text('');
		this.el.find('.body').text('');
		this.el.find('a').hide();
	}
};