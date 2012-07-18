<?php

use Symfony\Component\HttpFoundation\Response;
require_once(__DIR__."/../models/epub.php");
require_once __DIR__.'/../../vendor/markdownify/markdownify_extra.php';

$reader=$app["controllers_factory"];
$reader->value('chap',0);

$reader->get("/{id}/{chap}",function ($id) use ($app) {

	$md = new Markdownify_Extra(MDFY_LINKS_EACH_PARAGRAPH, MDFY_BODYWIDTH, FALSE);
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
				$page = $md->parseString($epub->getContentByName($content["href"]));
				if (substr($page,0,4)!="<svg")
					$pages[] = markdown($page);
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
		return print_r($epub->getOPF()->OPF,TRUE);
	}

	return $book;
});

function markdown($text) {
	$out = "";
	$text = preg_replace('/\{([^}]*)\}/', '', $text);
	//$links = extract_links($text);
	$text = explode("\n\n",$text);
	foreach ($text as $paragraph) {
		$paragraph = trim($paragraph);
		if (substr($paragraph,0,2)=="# ")
			$out .= preg_replace('/^# (.*)/','<h1>$1</h1>',$paragraph);
		else if (substr($paragraph,0,2)=="##")
			$out .= preg_replace('/^## (.*)/','<h2>$1</h2>',$paragraph);
		else {
			$par = preg_replace('/\*([^*]*)\*/', '<em>$1</em>', $paragraph);
			$par = preg_replace('/\[([^\]]*)\]\[([0-9]*)\]/', '<a href="$2">$1</a>', $par);
			$par = preg_replace('/\[(.*)\]:\ (.*)/', '', $par);
			$par = preg_replace('/\n /', '', $par);
			$out .= "<p>".$par."</p>";
		}
	}

	return $out;
}

function extract_links($text) {
	$links = array();
	preg_match_all('/\[(.*)\]:\ (.*)/', $text, $matches);
	if (count($matches[0]))
		foreach ($matches[1] as $kk=>$vv)
			$links[$vv] = $matches[2][$kk];
	return $links;
}

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