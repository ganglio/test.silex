<?php

use Symfony\Component\HttpFoundation\Response;
require_once(__DIR__."/../models/epub.php");

$reader=$app["controllers_factory"];
$reader->assert('id','book-id-([0-9a-f]*)');

$reader->get("/{id}",function ($id) use ($app) {

	$cover = "";
	$pages = array();

	$book=getFileFromId($id);

	/*if ($book) {
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
	}*/

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

		foreach ($OPF['spine'] as $item) {
			$item = $epub->getOPF()->getById($item);
			$bookData['componentsIndex'][] = $item['href'];

			$content = str_replace(array("\n","\r","\t"), '', $epub->getContentByName($item['href']));

			preg_match('/<title>(.*)<\/title>/', $content, $title);

			$bookData['contents'][] = array(
				'src' => $item['href'],
				'title' => $title[1],
			);

			preg_match('/<body[^>]*?>(.*)<\/body>/', $content, $body);

			$bookData['components'][$item['href']] = $body[1];
		}

		$bookData['metadata'] = $OPF['metadata'];

		$out = json_encode($bookData);
	}

	return new Response($out,200,array(
		'Content-Type' => 'application/json',
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