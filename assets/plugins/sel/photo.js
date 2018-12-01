var photo = {
  rect: {
    0: null,
    1: null
  },
  negativeCoordinates: false,
  drawable: true,
  
  onLoad: function () {
    // center image
    let img = $('img');
    $('.photo-wrapper').css({
      width: img.width(),
      'margin-left': '-' + img.width()/2 + 'px'
    });
    $('.photo-panel-position-wrapper').css({
      'margin-left': '50%'
    });
  },
  
  onMouseDown: function(event, img) {
    // drawing = true;
    if (!this.drawable) {
      return;
    }
    
    this.rect[0] = this.getCoordinates(event);
  },
  
  
  onDrag: function(event, img) {
    // if (!drawing) {
    //   return;
    // }
    if (!this.drawable) {
      return;
    }
    
    this.rect[1] = this.getCoordinates(event);
    this.drawRect(img);
  },
  
  
  onMouseUp: function(event, img) {
    let self = this;
    if (!this.drawable) {
      return;
    }
    let rectObj = $('.active-rect');
  
    let onStopDragResize = function(event, ui) {
      rectCoordinates = {
        topLeft: {
          x: ui.position.left,
          y: ui.position.top
        },
        bottomRight: {
          x: ui.position.left + rectObj.width(),
          y: ui.position.top + rectObj.height()
        },
        width: rectObj.width(),
        height: rectObj.height()
      };
      let form = $('.add-item-form');
      form.find('input#coordinates').val(self.getListOfCoordinates());
    };
    
    rectObj.css({
      cursor: 'pointer'
    });
    rectObj.resizable({
      containment: "parent",
      stop: onStopDragResize
    });
    rectObj.draggable({
      containment: "parent",
      stop: onStopDragResize
    });
  
    rectCoordinates = {
      topLeft: {
        x: this.pxToInt(rectObj.css('left')),
        y: this.pxToInt(rectObj.css('top'))
      },
      bottomRight: {
        x: this.pxToInt(rectObj.css('left')) + rectObj.width(),
        y: this.pxToInt(rectObj.css('left')) + rectObj.height()
      },
      width: rectObj.width(),
      height: rectObj.height()
    };
  
    this.negativeCoordinates = false;
    this.drawable = false;
    
    // activate form
    let form = $('.add-item-form');
    form.find('input#coordinates').val(this.getListOfCoordinates());
    form.find('#submit-button').removeAttr('disabled');
  },
  
  
  drawRect: function(img) {
    let rectObj = $('.active-rect');
    
    // workaround for last drag event zero coordinates
    let coordinates = this.getCoordinates(event);
    if (coordinates.x <= 0 && coordinates.y <= 0 && !this.negativeCoordinates) {
      this.negativeCoordinates = true;
      return;
    }
    
    
    
    let topLeft = {
      x: Math.max(Math.min(this.rect[0].x, this.rect[1].x), 0),
      y: Math.max(Math.min(this.rect[0].y, this.rect[1].y), 0)
    };
    
    let bottomRight = {
      x: Math.min(Math.max(this.rect[0].x, this.rect[1].x), img.width),
      y: Math.min(Math.max(this.rect[0].y, this.rect[1].y), img.height)
    };
    
    rectObj.css({
      top: topLeft.y,
      left: topLeft.x,
      width: Math.abs(topLeft.x - bottomRight.x),
      height: Math.abs(topLeft.y - bottomRight.y),
      display: 'block'
    });
  },
  
  getRectCoordinates: function (img) {
    return {
      topLeft: {
        x: Math.max(Math.min(this.rect[0].x, this.rect[1].x), 0),
        y: Math.max(Math.min(this.rect[0].y, this.rect[1].y), 0)
      },
      bottomRight: {
        x: Math.min(Math.max(this.rect[0].x, this.rect[1].x), img.width),
        y: Math.min(Math.max(this.rect[0].y, this.rect[1].y), img.height)
      }
    }
  },
  
  getCoordinates: function(event) {
    return {
      x: event.offsetX,
      y: event.offsetY
    };
  },
  
  
  getPercents: function(point, imgSize) {
    return Math.round(point / imgSize * 1000000) / 10000;
    
    // return {
    //   x: Math.round(event.offsetX / img.width * 10000) / 100,
    //   y: Math.round(event.offsetY / img.height * 10000) / 100
    // };
  },
  
  pxToInt: function (px) {
    return parseInt(px.slice(0, -2));
  },
  
  
  
  
  
  
  addItem: function () {
    console.log(100, this.getListOfCoordinates());
    let selectedItemType = $('.add-item-form select#itemType');
  
    this.makePassive(selectedItemType);
  
    // create new active rect
    this.drawable = true;
    $('.photo-wrapper').append('<div class="rect active-rect"></div>');
  
    // deactivate form
    let form = $('.add-item-form');
    form.find('input#coordinates').val('');
    form.find('#submit-button').attr('disabled', 'disabled');
    
    // add to items list
    let itemsList = $('.items');
    itemsList.find('li#item-' + selectedItemType.val() + ' a').css({
      visibility: 'visible'
    });
    let countOfItemsOfThisType = $('.passive-rect.rect-type-' + selectedItemType.val()).length;
    itemsList.find('li#item-' + selectedItemType.val() + ' a').html(
      selectedItemType.find('option:selected').text() + '  <span class="tag is-light">' + countOfItemsOfThisType + '</span>');
  },
  
  getListOfCoordinates: function () {
    let img = $('img');
    return this.getPercents(rectCoordinates.topLeft.x, img.width()) + ',' + this.getPercents(rectCoordinates.topLeft.y, img.height()) + ',' + // top left
      this.getPercents(rectCoordinates.topLeft.x + rectCoordinates.width, img.width()) + ',' + this.getPercents(rectCoordinates.topLeft.y, img.height()) + ',' + // top right
      this.getPercents(rectCoordinates.topLeft.x + rectCoordinates.width, img.width()) + ',' + this.getPercents(rectCoordinates.topLeft.y + rectCoordinates.height, img.height()) + ',' + // bottom right
      this.getPercents(rectCoordinates.topLeft.x, img.width()) + ',' + this.getPercents(rectCoordinates.topLeft.y + rectCoordinates.height, img.height()); // bottom left
  },
  
  makePassive: function (selectedItemType) {
    let rectObj = $('.active-rect');
    let rectId = Math.floor(Math.random() * Math.floor(10000));
    
    rectObj.css({
      display: 'none'
    });
    rectObj.addClass('passive-rect');
    rectObj.removeClass('active-rect');
    rectObj.addClass('rect-type-' + selectedItemType.val());
    rectObj.attr('id', 'rect-' + rectId);
    rectObj.draggable('destroy');
    rectObj.resizable('destroy');
  }
};