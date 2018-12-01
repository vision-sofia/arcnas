var photo = {
  rect: {
    0: null,
    1: null
  },
  negativeCoordinates: false,
  drawable: true,
  
  onLoad: function () {
    let img = $('img');
    let photoWrapper = $('.photo-wrapper');
    photoWrapper.css({
      width: img.width()
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
    if (!this.drawable) {
      return;
    }
    
    let rectObj = $('.active-rect');
    rectObj.css({
      cursor: 'pointer'
    });
    // rectObj.resizable();
    rectObj.draggable({
      containment: "parent",
      stop: function (event, ui) {
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
        console.log(rectCoordinates);
      }
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
    console.log(rectCoordinates);
  
    this.negativeCoordinates = false;
    this.drawable = false;
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
    
    this.makePassive();
  },
  
  getListOfCoordinates: function () {
    let img = $('img');
    return this.getPercents(rectCoordinates.topLeft.x, img.width()) + ', ' + this.getPercents(rectCoordinates.topLeft.y, img.height()) + ', ' + // top left
      this.getPercents(rectCoordinates.topLeft.x + rectCoordinates.width, img.width()) + ', ' + this.getPercents(rectCoordinates.topLeft.y, img.height()) + ', ' + // top right
      this.getPercents(rectCoordinates.topLeft.x + rectCoordinates.width, img.width()) + ', ' + this.getPercents(rectCoordinates.topLeft.y + rectCoordinates.height, img.height()) + ', ' + // bottom right
      this.getPercents(rectCoordinates.topLeft.x, img.width()) + ', ' + this.getPercents(rectCoordinates.topLeft.y + rectCoordinates.height, img.height()); // bottom left
  },
  
  makePassive: function () {
    let rectObj = $('.active-rect');
    let selectedItemType = $('.add-item-form select[name="itemType"]');
    let rectId = Math.floor(Math.random() * Math.floor(10000));
    
    rectObj.css({
      display: 'none'
    });
    rectObj.addClass('passive-rect');
    rectObj.removeClass('active-rect');
    rectObj.addClass('rect-type-' + selectedItemType.val());
    rectObj.attr('id', 'rect-' + rectId);
    rectObj.draggable('destroy');

    rectObj.find('.label').text(selectedItemType.text());
    rectObj.find('.label').attr('title', selectedItemType.text());
    
    // create new active rect
    this.drawable = true;
    $('.photo-wrapper').append('<div class="rect active-rect"><span class="label" title=""></span></div>');
    
    
  }
};