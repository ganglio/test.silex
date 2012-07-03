<?php

$header=$app["controllers_factory"];
$header->get("/", function() use ($app) {
	return $app["twig"]->render("header.html.twig",array(
		"title"=>"lect.io",
		"menu"=>array(
			"login"=>array(
				"title"=>"login",
				"href"=>"#login",
			),
		),
		"pippo"=>$app["pippo"],
	));
});

return $header;