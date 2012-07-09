<?php

$content=$app["controllers_factory"];
$content->get("/",function () use ($app) {
	for ($i=0; $i<100; $i++)
		$books[]="<article>$i</article>";
	return $app["twig"]->render("content.html.twig",array(
		"books"=>$books
	));
});

return $content;