jQuery('.typo3-neos-nodetypes-image a.fancybox, .typo3-neos-nodetypes-textwithimage a.fancybox, .gsl-duttweilerde-multipleimages a.fancybox').fancybox({
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

jQuery(document).ready(function() {
	var back_to_top_button = ['<a href="#top" class="back-to-top">Nach oben</a>'].join("");
	$('body').append(back_to_top_button);
	$('.back-to-top').hide();

	$(function() {
		$(window).scroll(function() {
			if ($(this).scrollTop() > 200) {
				$('.back-to-top').fadeIn();
			} else {
				$('.back-to-top').fadeOut();
			}
		});
		$('.back-to-top').click(function() {
			$('body, html').animate({ scrollTop: 0 }, 800);
			return false;
		});
	});
});
