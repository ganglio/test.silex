<?php

$library=$app["controllers_factory"];
$library->get("/",function () use ($app) {
	for ($i=0; $i<40; $i++)
		$books[]=$app["twig"]->render("cover.html.twig", array(
			"bookid"=>1,
			"date"=>(rand()%255),
			"title"=>(rand()%255),
			"author"=>(rand()%255),
			"cover"=>"/images/cover.jpg",
		));
//$books[]="<article data-id='book-".$i."' data-title='".(rand()%255)."' data-date='".(rand()%255)."' data-author='".(rand()%255)."'>$i</article>";
	return $app["twig"]->render("library.html.twig",array(
		"books"=>$books
	));
});

return $library;