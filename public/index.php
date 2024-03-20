<?php

use App\Core\Core;

include_once dirname(__DIR__) . '/src/Core/autoload.php';

$handler = new Core();
$handler->start();