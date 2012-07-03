<?php

$header=$app["controllers_factory"];
$header->get("/", function() {
	return $app["twig"]->render("header.html.twig",array(
		"topo"=>"Il cazzo",
		"puzza"=>"culo",
		"navs"=>array(
			"pippo",
			"pluto",
		),
	));
})->bind("header");

return $header;