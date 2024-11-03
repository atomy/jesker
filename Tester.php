<?php

require_once 'vendor/autoload.php';

$dbService = new \IOGames\Jesker\Service\Database();
$players = $dbService->init()->getPlayers();

var_dump($players); die();