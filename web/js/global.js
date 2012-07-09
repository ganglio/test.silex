$(document).ready(function(){
	$("section").masonry({
		itemSelector: 'article',
		isFitWidth: true
	});
	$(document).scrollTop(40);
});