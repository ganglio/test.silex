<?php

use Symfony\Component\HttpFoundation\Response;
require_once(__DIR__."/../models/epub.php");

$reader=$app["controllers_factory"];

$reader->get("/{id}",function ($id) use ($app) {
	$cover = "";
	$book = "";
	$pages = array();

	if ($dh = opendir(BOOKS_FOLDER)) {
		while ($filename = readdir($dh))
			if (strpos($filename,"epub") && $id=="book-id-".md5($filename)) {
				$book = $filename;
			}
	}

	if ($book) {
		$epub = new EPUB(BOOKS_FOLDER.$book);
		$cover = "data:image/jpeg;base64,".base64_encode($epub->getCover());

		$spine = $epub->getOPF()->OPF["spine"];
		foreach ($spine as $section) {
			$content = $epub->getOPF()->getById($section);
			$pages[] = $epub->getContentByName($content["href"]);
		}
	}

	return $app["twig"]->render("reader.html.twig",array(
		"cover"=>$cover,
		"pages"=>$pages
	));
});

return $reader;