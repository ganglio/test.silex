<?php

$content=$app["controllers_factory"];
$content->get("/",function () use ($app){
	return $app["twig"]->render("content.html.twig");
});

return $content;