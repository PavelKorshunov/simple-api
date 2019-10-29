<?php

require "../vendor/autoload.php";

use App\Kernel;

$kernel = new Kernel();

try {
    $parameters = $kernel->run()->matchRoute($_SERVER["REQUEST_URI"]);
    $kernel->createController($parameters["_controller"]);

} catch (Exception $e) {
    echo $e->getMessage();
}