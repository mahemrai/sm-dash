<?php

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

require_once __DIR__ . '/../vendor/autoload.php';

class Slim_Framework_TestCase extends PHPUnit_Framework_TestCase {
    private $testingMethods = array('get', 'post');

    public function setup() {
        $app = new \Slim\Slim(array(
            'version' => '0.0.0',
            'debug' => false,
            'mode' => 'testing',
            'templates.path' => __DIR__ . '/../app/views'
        ));

        require __DIR__ . '/../app/routes/index.php';
        require __DIR__ . '/../app/routes/twitter.php';
        require __DIR__ . '/../app/routes/scoopit.php';
        require __DIR__ . '/../app/routes/settings.php';

        require __DIR__ . '/../app/models/Accounts.php';

        $this->app = $app;
    }

    private function request($method, $path, $formVars = array(), $optionalHeaders = array()) {
        ob_start();

        \Slim\Environment::mock(array_merge(array(
            'REQUEST_METHOD' => strtoupper($method),
            'PATH_INFO' => $path,
            'SERVER_NAME' => 'locahost::8117',
            'slim.input' => http_build_query($formVars)
        ), $optionalHeaders));

        $this->request = $this->app->request();
        $this->response = $this->app->response();

        $this->app->run();
    }

    public function __call($method, $arguments) {
        if(in_array($method, $this->testingMethods)) {
            list($path, $formVars, $headers) = array_pad($arguments, 3, array());
            return $this->request($method, $path, $formVars, $headers);
        }
        throw new \BadMethodCallException(strtoupper($method) . ' is not supported');
    }
}