$=jQuery;

$(document).ready(function() {
  loadToggled();
});

// If toggled, load toggled on next pages
function loadToggled() {
  // Test if local storage is available
  if (typeof(Storage) !== 'undefined') {
    // If user has never been on this page default to toggle open
    if (localStorage.getItem('study-form-toggle') == null) {
      $('.group-toggle__tab').each( function() {
        var targetId = $(this).data('toggle-id');
        $('#'+targetId).removeClass('group-toggle-target--toggled');
        $(this).parent('.group-toggle').removeClass('group-toggle--toggled');
      });
    }
    // If toggle is set to open add class
    if (localStorage.getItem('study-form-toggle') == 'open') {
      $('.group-toggle__tab').each( function() {
        var targetId = $(this).data('toggle-id');
        $('#'+targetId).removeClass('group-toggle-target--toggled');
        $(this).parent('.group-toggle').removeClass('group-toggle--toggled');
      });
    }
  }
}
