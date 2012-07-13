<?php

use Symfony\Component\HttpFoundation\Response;
require_once(__DIR__."/../models/epub.php");

define("BOOKS_FOLDER",__DIR__."/../books/");

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

$library->get("/booksinfo", function() use($app) {
	$zip=new ZipArchive();
	if ($dh = opendir(BOOKS_FOLDER)) {
		while ($filename = readdir($dh))
			if (strpos($filename,"epub")) {
				$epub = new EPUB(BOOKS_FOLDER.$filename);
				print_r($epub->getOPF());
			}
	} else
		return new Response("Unable to open folder",404);
});

return $library;