jQuery('.typo3-neos-nodetypes-image a.lightbox, .typo3-neos-nodetypes-textwithimage a.lightbox').attr('rel', 'gallery').fancybox({
	helpers: {
		title: {
			type: 'inside'
		}
	}
});
jQuery(".fancybox-various").fancybox({
	maxWidth	: 800,
	maxHeight	: 600,
	fitToView	: false,
	width		: '70%',
	height		: '70%',
	autoSize	: false,
	closeClick	: false,
	openEffect	: 'none',
	closeEffect	: 'none',
	helpers		: {
		title	: {
			type	: 'inside'
		}
	}
});
