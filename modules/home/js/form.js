(function() {
  'use strict';

  var load = function () {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }
  window.addEventListener('mousemove', load, false);
  window.addEventListener('load', load, false);
  window.addEventListener('keypress', load, false);
})();
