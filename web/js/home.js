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
	$(".sort a").click(function(){
		var $bookShelf = $("section");
		var $bookShelfClone = $bookShelf.clone();

		var $this=$(this);
		var sortby=$this.attr("sortby")

		$this.siblings().removeClass("selected");
		$this.addClass("selected");

		$orderedBookShelf=$bookShelfClone.find("article").sorted({
			by: function(v) {
				return $(v).data(sortby);
			}
		});

		$bookShelf.quicksand($orderedBookShelf, {
			duration: 800,
			easing: 'linear'
		},function(){
			$("section").css({"height":""});
		});
	});

	$(".search input").keyup(function(){
		$books = $("section article");
		var keyword = $(this).val();
		$books.each(function(i,t){
			var regex=new RegExp(keyword,"gi");
			var matchAuthor = (""+$(t).data("author")+"").match(regex);
			var matchTitle = (""+$(t).data("title")+"").match(regex);

			if (matchAuthor || matchTitle)
				$(t).show(500);
			else
				$(t).hide(500);
		});
	});
	$(".search a.clear").click(function(){
		$(".search input").val("").keyup();
	});
});