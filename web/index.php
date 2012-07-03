<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
use Symfony\Component\HttpFoundation\Request;

// Debug
$app['debug'] = true;

/**
 * Before action
 */
$app->before(function () use ($app) {
	// Registering Twig
	$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path'         => __DIR__.'/../views',
		'twig.class_path'   => __DIR__.'/../vendor/twig/lib',
		// 'twig.options'      => array('cache' => __DIR__ .'/cache'),
	));
	$app["pippo"] = 12;
	// Registering URLGenerator
	$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
});

// 404
$app->error(function (\Exception $e, $code) use ($app) {
	if (404 == $code)
		return $app->redirect("/");
});

$app->mount("/header", include 'controllers/header.php');
$app->mount("/content", include 'controllers/content.php');

// Root
$app->get('/', function () use($app) {
	return $app["twig"]->render("home.html.twig",array("pippo"=>$app["pippo"]));
})->bind("home");

// Run
$app->run();