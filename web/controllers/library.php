<?php

$library=$app["controllers_factory"];
$library->get("/",function () use ($app) {
	for ($i=0; $i<10; $i++)
		$books[]="<article data-id='book-".$i."' data-title='".(rand()%255)."' data-date='".(rand()%255)."' data-author='".(rand()%255)."'>$i</article>";
	return $app["twig"]->render("library.html.twig",array(
		"books"=>$books
	));
});

return $library;