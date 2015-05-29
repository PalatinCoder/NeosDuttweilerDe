var resizeTimer;
$(window).resize(function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(checkWindowSize(), 100);
});

function checkWindowSize() {
	if ($(window).width() <= 820) {
		makeSiteResponsive();
	}
	if ($(window).width() >= 821) {
		restoreDefault();
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

	$('#NewsColumnExpand').hide();
	$('#NewsColumnResponsiveHeading').click(function() {
		$('#NewsColumn').slideToggle();
		$('#NewsColumnCollapse').toggle();
		$('#NewsColumnExpand').toggle();
	});
}
function restoreDefault() {
	$(document).off('click');
	$('#IndexPageTopMenu').show();
	$('#PageHeading').height('200px');
}
