<?php

define('DEFT_PLATFORM', 'development');

// augment include path
$paths = explode(PATH_SEPARATOR, get_include_path());
$deft = realpath(__DIR__ . '/..');

if (!in_array($deft, $paths)) {
    array_unshift($paths, $deft);
    set_include_path(join(PATH_SEPARATOR, $paths));
}

include_once 'deft/autoloader.php';
spl_autoload_register(new Deft\AutoLoader);
