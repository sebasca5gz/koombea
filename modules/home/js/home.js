$(document).ready((function() {
  const BODY = $('body');

  getValues = (form) => {
    let values = {};
    form.find('input[type=text], input[type=email], input[type=password]').each(function(index, element){
      values[$(element).attr('name')] = $(element).val();
    });
    return values;
  }

  refresh = (data) => {
    console.log(data);
  }

  replaceContent = (data) => {
    BODY.find('.content-page').html(data);
  }

  error = (data) => {
    alert('Error al procesar la solicitud');
  }

  clearForm = () => {
    BODY.find('input[type=text], input[type=email], input[type=password]').each(function(index, element){
      $(element).val('');
    });
  }

  success = (data) => {
    data = JSON.parse(data);
    switch(data.option) {
      case 'loginForm':
      case 'registerForm': replaceContent(data.response); break;
      case 'message':
        $.fancybox.open(data.message);
        if (!data.error) {
          clearForm();
        }
      break;
      case 'refresh': window.location.reload(); break;
    }
  }

  fail = (data) => {
    alert('error al procesar la solicitud')
  }

  ajax = (params) => {
    params.ajax = true;
    $.ajax({
      method: "POST",
      url: window.location.href,
      data: params,
      success: (data) => success(data),
      fail: (data) => fail(data),
    });
  }

  BODY.on('click', '.nav-link', function(e) {
    e.preventDefault();
    let option = $(this).attr('data-option');
    BODY.find('.nav-link').removeClass('active');
    $(this).addClass('active');
    ajax({
      module: 'home',
      option: option,
      data: getValues($(this))
    });
  });

  BODY.on('submit', '.registerForm form, .loginForm form', function(e) {
    e.preventDefault();
    let option = $(this).attr('data-option');
    ajax({
      module: 'home',
      option: option,
      data: getValues($(this))
    });
  });
}));