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