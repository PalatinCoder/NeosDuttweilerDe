var resizeTimer;
$(window).resize(function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(checkWindowSize(), 100);
});

function checkWindowSize() {
	if ($(window).width() <= 820) {
		makeSiteResponsive();
	}
}

function makeSiteResponsive() {
	$('#PageHeading').height(0.25*$('#PageHeading').width());
	$('#IndexPageTopMenu').hide();

	$(document).click(function() {
		$('#IndexPageTopMenu').hide('slide');
	});
	$('#menuButton').click(function(event) {
		$('#IndexPageTopMenu').show('slide');
		event.stopPropagation();
	});
}
