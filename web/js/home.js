// Custom sorting plugin
(function($) {
  $.fn.sorted = function(customOptions) {
    var options = {
      reversed: false,
      by: function(a) { return a.text(); }
    };
    $.extend(options, customOptions);
    $data = $(this);
    arr = $data.get();
    arr.sort(function(a, b) {
      var valA = options.by($(a));
      var valB = options.by($(b));
      if (options.reversed) {
        return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;				
      } else {		
        return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;	
      }
    });
    return $(arr);
  };
})(jQuery);

$(function(){
	var $books = $("section");
	var $data = $books.clone();

	$(".sort a").click(function(){
		var $this=$(this);
		var sortby=$this.attr("sortby")

		$this.siblings().removeClass("selected");
		$this.addClass("selected");

		$orderedBooks=$data.find("article").sorted({
			by: function(v) {
				return $(v).data(sortby);
			}
		});

		$books.quicksand($orderedBooks, {
			duration: 800,
			easing: 'easeInOutQuad'
		});
	});
});