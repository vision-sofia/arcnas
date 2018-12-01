var rect = {
  lastCoordinates: null,
  onMouseDown: function(event, img) {
    this.lastCoordinates = this.getCoordinates(event);
  },
  onDrag: function(event, rect) {
    // if (!this.lastCoordinates) {
    //   this.lastCoordinates = this.getCoordinates(event);
    //   return;
    // }
    
    let currentCoordinates = this.getCoordinates(event);
  
  
    let diffX = this.lastCoordinates.x - currentCoordinates.x;
    let diffY = this.lastCoordinates.y - currentCoordinates.y;
  
    this.lastCoordinates = currentCoordinates;
    
    // this.rect[1] = this.getCoordinates(event);
    if (!(diffX === 0 && diffY === 0)) {
      this.drawRect(rect, diffX, diffY);
    }
  },
  
  drawRect: function(rect, diffX, diffY) {
    let rectObj = $(rect);
    //
    // // workaround for last drag event zero coordinates
    // let coordinates = this.getCoordinates(event);
    // if (coordinates.x <= 0 && coordinates.y <= 0 && !this.negativeCoordinates) {
    //   this.negativeCoordinates = true;
    //   return;
    // }
    
    console.log({
      top: parseInt(rectObj.css('top').slice(0, -2)) + diffX,
      left: parseInt(rectObj.css('left').slice(0, -2)) + diffY
    });
    
    // rectObj.css({
    //   top: parseInt(rectObj.css('top').slice(0, -2)) + diffX,
    //   left: parseInt(rectObj.css('left').slice(0, -2)) + diffY
    // });
  },
  
  
  getCoordinates: function(event) {
    return {
      x: event.offsetX,
      y: event.offsetY
    };
  },
  
  
  getPercents: function(event, img) {
    return {
      x: Math.round(event.offsetX / img.width * 10000) / 100,
      y: Math.round(event.offsetY / img.height * 10000) / 100
    };
  }
}