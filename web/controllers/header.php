<?php

$header=$app["controllers_factory"];
$header->get("/", function() use ($app) {
	return $app["twig"]->render("header.html.twig",array(
		"title"=>"Il cazzo",
	));
});

return $header;