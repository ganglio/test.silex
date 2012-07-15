<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
use Symfony\Component\HttpFoundation\Request;

define("BOOKS_FOLDER",__DIR__."/books/");

// Debug
$app['debug'] = TRUE;

/**
 * Before action
 */
$app->before(function () use ($app) {
	$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path'         => __DIR__.'/../views',
		'twig.class_path'   => __DIR__.'/../vendor/twig/lib',
		// 'twig.options'      => array('cache' => __DIR__ .'/cache'),
	));
	//$app->register(new Silex\Provider\SecurityServiceProvider());
	$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
});

// 404
$app->error(function (\Exception $e, $code) use ($app) {
	if (404 == $code)
		return $app->redirect("/");
});

$app->mount("/header", include 'controllers/header.php');
$app->mount("/library", include 'controllers/library.php');
$app->mount("/read", include 'controllers/reader.php');

// Root
$app->get('/', function () use($app) {
	return $app["twig"]->render("home.html.twig");
})->bind("home");

$app->get("/cacca", function() use($app){
	return "cacca";
});
// Run
$app->run();