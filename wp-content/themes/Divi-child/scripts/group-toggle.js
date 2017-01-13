$=jQuery;

$(document).ready(function() {
  groupToggle();
});

function groupToggle() {
  $('.group-toggle__tab').on( 'click', function() {
    var targetId = $(this).data('toggle-id');
    $('#'+targetId).slideToggle();
    $(this).parent('.group-toggle').toggleClass('group-toggle--toggled');

    // Test if local storage is available
    if (typeof(Storage) !== 'undefined') {
      // Store if the form is toggled or not
      if (localStorage.getItem('study-form-toggle') == 'closed') {
        localStorage.setItem('study-form-toggle', 'open');
      } else {
        localStorage.setItem('study-form-toggle', 'closed');
      }
    }
  });
}
