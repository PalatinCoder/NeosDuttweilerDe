jQuery('.typo3-neos-nodetypes-image a.lightbox, .typo3-neos-nodetypes-textwithimage a.lightbox').attr('rel', 'gallery').fancybox({
	helpers: {
		overlay: {
			css: {
				'background': 'rgba(58, 42, 45, 0.95)'
			}
		},
		title: {
			type: 'inside'
		}
	},
	beforeLoad: function() {
		this.title = $(this.element).attr('title');
	}
});
