<?php
require '../vendor/autoload.php';
require '../config/database.php';
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

$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new Twig_Extension_Debug(),
    new Twig_Extension_TwitterHelper()
);

Less_Autoloader::register();

$parser = new Less_Parser();
$parser->parseFile('css/styles.less', 'css');
$css = $parser->getCss();
file_put_contents('css/styles.css', $css);

require '../app/routes/index.php';

$app->run();
?>
