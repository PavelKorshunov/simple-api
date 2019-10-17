<?php

require "../vendor/autoload.php";

use App\Kernel;

$kernel = new Kernel();

try {
    $parameters = $kernel->run()->matchRoute($_SERVER["REQUEST_URI"]);

    $main = new $parameters["_controller"];
    $main();
} catch (Exception $e) {
    echo $e->getMessage();
}