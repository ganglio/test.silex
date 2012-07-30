$(document).ready(function(){
	function bookDataMaker(bookData) {
		return {
			getComponents: function () {
				return bookData.componentsIndex;
			},
			getContents: function () {
				return bookData.contents;
			},
			getComponent: function (componentId) {
				return bookData.components[componentId];
			},
			getMetaData: function(key) {
				return bookData.metadata[key];
			}
		}
	}

	var book_id = window.location.href.split('/').reverse()[0];

	$.get('/read/info/'+book_id,function(book) {
		Monocle.Reader('book',bookDataMaker(book), { stylesheet: "p { padding:0; margin:3px; }" });
	});
});