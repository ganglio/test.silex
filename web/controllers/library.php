<?php

use Symfony\Component\HttpFoundation\Response;
require_once(__DIR__."/../models/epub.php");

$library=$app["controllers_factory"];

$library->get("/",function () use ($app) {
	if ($dh = opendir(BOOKS_FOLDER)) {
		while ($filename = readdir($dh))
			if (strpos($filename,"epub")) {
				$epub = new EPUB(BOOKS_FOLDER.$filename);
				$cover = "data:image/jpeg;base64,".base64_encode($epub->getCover());
				$date=strtotime($epub->getOPF()->OPF["metadata"]["date"]);
				$books[]=$app["twig"]->render("cover.html.twig", array(
					"bookid"=>"id-".md5($filename),
					"date"=>date("d/m/Y",$date),
					"sortdate"=>date("Ymd",$date),
					"title"=>$epub->getOPF()->OPF["metadata"]["title"],
					"author"=>$epub->getOPF()->OPF["metadata"]["author"],
					"cover"=>$cover,//"/images/default_cover.jpg",
				));
			}
	} else
		return new Response("Unable to open folder",404);

	return $app["twig"]->render("library.html.twig",array(
		"books"=>$books
	));
});

return $library;