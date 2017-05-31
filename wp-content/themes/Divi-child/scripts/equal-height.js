$=jQuery;

equalheight = function(container){
  var currentTallest = 0,
       currentRowStart = 0,
       rowDivs = new Array(),
       $el,
       topPosition = 0;

  $(container).each(function() {
   $el = $(this);
   $($el).height('auto')
   topPostion = $el.position().top;
   if (currentRowStart != topPostion) {
     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0; // empty the array
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }

  for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
   rowDivs[currentDiv].height(currentTallest);
  }
  });
}

$(window).on("load", function() {
  equalheight('.eq-height article .entry-featured-image-url');
  equalheight('.eq-height .category-areas-of-focus');
});

$( window ).resize(function() {
  equalheight('.eq-height article .entry-featured-image-url');
  equalheight('.eq-height .category-areas-of-focus');
});