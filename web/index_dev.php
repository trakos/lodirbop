<?php

// when using php built-in web server uncomment this to serve as a router
/*$path = realpath($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"]);
if (!empty($_SERVER["REQUEST_URI"]) && !is_dir($path) && file_exists($path) && $path != __FILE__) {
    return false;
}*/
require_once __DIR__ . "/../app/autoload.php";

$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$kernel = new AppKernel('dev', true);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);