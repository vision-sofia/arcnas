var itemsList = {
  onMouseOver: function(rectType) {
    $('.passive-rect.rect-type-' + rectType).css({
      display:'block',
      opacity: 1
    });
  },
  onMouseLeave: function(rectType) {
    $('.passive-rect.rect-type-' + rectType).css({
      display: 'none',
      // opacity: 0.5
    });
  }
};