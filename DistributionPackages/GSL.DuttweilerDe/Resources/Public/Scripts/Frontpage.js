$(function() {
    $('#menuButton').click(function() {
      $('#IndexPageTopMenu').toggleClass('is-open');
      $('#menuButton').toggleClass('is-open');
    });
    $('#NewsColumnResponsiveHeading').click(function() {
      $('#NewsColumn').toggleClass('is-closed');
      $('#NewsColumnResponsiveHeading').toggleClass('is-closed');
    });
});