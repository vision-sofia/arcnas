var activeRectCoordinates = null;

window.onload = function () {
  // rectCtrl.drawPassiveRects(passiveRects);
};


var userCtrl = {
  
  signIn: function () {
    $('.login-form').submit();
  },
  
  signUp: function () {
    $('.register-form').submit();
  }
  
};

var searchCtrl = {
  
  search: function () {
    let valid = true;
    let elementVal = $('.search-form .itemType select').val();
    if (!elementVal) {
      $('.search-form .itemType .select').addClass('is-danger');
      valid = false;
    }
  
    if (valid) {
      $('.search-form').submit();
    }
    return;
  }
  
};

var uploadCtrl = {
  
  upload: function () {
    $('.upload-form').submit();
  },
  
};