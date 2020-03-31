<?php

$PUBLIC_ROOT = dirname(__DIR__);

include_once $PUBLIC_ROOT . '/vendor/autoload.php';


use App\AppCore;


try {
    new AppCore(
        include_once $PUBLIC_ROOT . '/src/app/config.php',
        include_once $PUBLIC_ROOT . '/src/app/router.php'
    );
} catch (Exception $e) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new Exception($e->getMessage());
}
