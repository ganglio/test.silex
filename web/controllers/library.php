<?php

$library=$app["controllers_factory"];
$library->get("/",function () use ($app) {
	for ($i=0; $i<40; $i++) {
		$date=rand();
		$books[]=$app["twig"]->render("cover.html.twig", array(
			"bookid"=>$i,
			"date"=>date("d/m/Y",$date),
			"sortdate"=>date("Ymd",$date),
			"title"=>(rand()%255),
			"author"=>(rand()%255),
			"cover"=>"/images/default_cover.jpg",
		));
	}

	return $app["twig"]->render("library.html.twig",array(
		"books"=>$books
	));
});

return $library;