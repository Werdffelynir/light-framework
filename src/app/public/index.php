<?php

include_once '../../../vendor/autoload.php';


use App\AppCore;


try {
    new AppCore(
        include_once '../config.php',
        include_once '../router.php'
    );
} catch (Exception $e) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new Exception($e->getMessage());
}
