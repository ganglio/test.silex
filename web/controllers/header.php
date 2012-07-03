<?php

$header=$app["controllers_factory"];
$header->before(function () use ($app) {
	$app["pippo"]=33;	
});
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
$header->get("/cacca", function () use ($app) {
	echo $app["pippo"];
})->before(function() use ($app) {
	$app["pippo"]=44;
});

return $header;