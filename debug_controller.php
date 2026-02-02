<?php

require __DIR__.'/vendor/autoload.php';

use App\Http\Controllers\Admin\AdminController;
use ReflectionMethod;

try {
    $r = new ReflectionMethod(AdminController::class, 'dashboard');
    $params = $r->getParameters();
    foreach ($params as $param) {
        echo 'Parameter: '.$param->getName()."\n";
        echo 'Type: '.$param->getType()."\n";
    }
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage();
}
