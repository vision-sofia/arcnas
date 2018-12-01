var rectCtrl = {
  drawPassiveRects: function (passiveRects) {
    let photoWrapper = $('.photo-wrapper');
    let img = photoWrapper.find('img');
    passiveRects.forEach(r => {
      photoWrapper.append('<div class="rect passive-rect rect-type-' + r.type + '" style="top: ' + photoCtrl.percentsToPx(r.topLeft.y, img.height()) + 'px; left: ' + photoCtrl.percentsToPx(r.topLeft.x, img.width()) + 'px; width: ' + photoCtrl.percentsToPx(r.width, img.width()) + 'px; height: ' + photoCtrl.percentsToPx(r.height, img.height()) + 'x; border-color: ' + itemTypes[r.type].color + '"></div>');
      itemsList.addItem(r.type);
    });
  },
  
  showPassiveRects: function(rectType) {
    $('.passive-rect.rect-type-' + rectType).css({
      display:'block',
      opacity: 0.75
    });
  },
  hidePassiveRects: function(rectType) {
    $('.passive-rect.rect-type-' + rectType).css({
      display: 'none',
      // opacity: 0.5
    });
  },
  
  showAllPassiveRects: function() {
    $('.passive-rect').css({
      display:'block',
      opacity: 0.75
    });
  },
  hideAllPassiveRects: function() {
    $('.passive-rect').css({
      display: 'none',
      // opacity: 0.5
    });
  },
}