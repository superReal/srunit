<?php

$pathsToAutoloader = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
);

foreach ($pathsToAutoloader as $path) {
    if (is_dir($path)) {
        require_once $path;
    }
}

