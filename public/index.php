<?php
require '../vendor/autoload.php';
require '../app/helpers/TwitterHelper.php';

//Slim app configuration
$app = new \Slim\Slim(array(
    'templates.path' => '../app/views',
    'view' => new \Slim\Views\Twig(),
));

//View configuration
$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'charset' => 'utf-8',
    'cache' => realpath('../app/views/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);

//Load Twig extensions
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new Twig_Extension_Debug(),
    new Twig_Extension_TwitterHelper()
);

//LESS
Less_Autoloader::register();

$parser = new Less_Parser();
$parser->parseFile('css/styles.less', 'css');
$css = $parser->getCss();
file_put_contents('css/styles.css', $css);

//start our session
session_start();

\BurningDiode\Slim\Config\Yaml::getInstance()->addFile('../config/app.yml');
$app->config('application');

//main application routes
require '../app/routes/index.php';

//twitter routes
require '../app/routes/twitter.php';

//scoopit routes
require '../app/routes/scoopit.php';

$app->run();
?>
