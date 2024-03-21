<?php

use App\Core\Core;

include_once dirname(__DIR__) . '/src/Core/autoload.php';

echo (new Core())->start();
