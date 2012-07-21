<?php

use Symfony\Component\HttpFoundation\Response;
require_once(__DIR__."/../models/epub.php");

$reader=$app["controllers_factory"];
$reader->value('chap',0);
$reader->assert('id','book-id-([0-9a-f]*)');

$reader->get("/{id}/{chap}",function ($id) use ($app) {

	$cover = "";
	$pages = array();

	$book=getFileFromId($id);

	if ($book) {
		$epub = new EPUB(BOOKS_FOLDER.$book);
		$cover = "data:image/jpeg;base64,".base64_encode($epub->getCover());

		$spine = $epub->getOPF()->OPF["spine"];

		foreach ($spine as $section) {
			$content = $epub->getOPF()->getById($section);
			$content_type = explode("/",$content["media-type"]);
			if ($content_type[0]!="image") {
				$pages[] = $epub->getContentByName($content["href"]);
			}
		}
	}

	//return print_r($pages,TRUE);

	return $app["twig"]->render("reader.html.twig",array(
		"pages"=>$pages
	));
});

$reader->get("/info/{id}",function ($id) use ($app) {
	$book = getFileFromId($id);

	if ($book) {
		$epub = new EPUB(BOOKS_FOLDER.$book);
		$OPF = $epub->getOPF()->OPF;
		$bookData = print_r($OPF,TRUE);
	}

	$bookData = $app['twig']->render('bookData.js.twig',array(
		'components' => $OPF['spine'],
		'contents' => $OPF['manifest'],
		'metadata' => $OPF['metadata'],
	));//*/

	return new Response($bookData,200,array(
		'Content-Type' => 'application/javascript',
	));
});

function getFileFromId($id) {
	$book=FALSE;
	if ($dh = opendir(BOOKS_FOLDER)) {
		while ($filename = readdir($dh))
			if (strpos($filename,"epub") && $id=="book-id-".md5($filename)) {
				$book = $filename;
			}
	}
	return $book;
}

return $reader;