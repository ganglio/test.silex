$(document).ready(function(){
	$("#book").turn({
		autoCenter: true
	});

	$(document).keyup(function(e){
		switch (e.keyCode) {
			case 37:
				$("#book").turn("previous");
			break;

			case 39:
				$("#book").turn("next");
			break;
		}
	});
});