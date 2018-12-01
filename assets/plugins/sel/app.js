var activeRectCoordinates = null;

var passiveRects = [{
  topLeft: {
    x: 25.22,
    y: 55
  },
  width: 19.26,
  height: 14.22,
  type: 'window'
}];

window.onload = function () {
  rectCtrl.drawPassiveRects(passiveRects);
};