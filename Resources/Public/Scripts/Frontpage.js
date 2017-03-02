$(function() {
    $('#menuButton').click(function() {
      $('#IndexPageTopMenu').toggleClass('is-open');
      $('#menuButton').toggleClass('is-open');
    });
    $('#NewsColumnResponsiveHeading').click(function() {
      $('#NewsColumn').toggleClass('is-closed');
      $('#NewsColumnResponsiveHeading').toggleClass('is-closed');
    });
    $.smartbanner({
      title: 'DuttweilerApp',
      author: 'Jan Syring-Lingenfelder',
      button: 'Installieren',
inGooglePlay: 'bei Google Play',
icon: 'http://static.duttweiler-net.de/wappen/wappen8.gif',
      price: 'Kostenlos'
    });
});