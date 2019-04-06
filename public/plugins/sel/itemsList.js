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
  },
  
  addItem: function (itemType) {
    let itemsList = $('.items');
    itemsList.find('li#item-' + itemType + ' a').css({
      display: 'block'
    });
    let countOfItemsOfThisType = $('.passive-rect.rect-type-' + itemType).length;
    itemsList.find('li#item-' + itemType + ' a').html(
      itemTypes[itemType].name + ' <span class="tag is-light">' + countOfItemsOfThisType + '</span> <span class="tag item-color-mark" style="color: ' + itemTypes[itemType].color + '">&bull;</span>');
  }
};