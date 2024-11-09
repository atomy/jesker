<?php

require_once 'vendor/autoload.php';

$main = new \IOGames\Jesker\Main();
$main->createWorker();
$main->run();