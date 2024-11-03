<?php

namespace IOGames\Jesker\Service;

use IOGames\Jesker\Service\Data\Database;
use IOGames\Jesker\Service\Rcon\Mapper;

/**
 * Class Jesker
 */
class Jesker
{
    public function getPlayerData()
    {
        $database = new Database();
        $dbPlayerCollection = $database->getPlayers();

        $rconMapper = new Mapper();
        return $rconMapper->getDataForPlayers($dbPlayerCollection);
    }
}