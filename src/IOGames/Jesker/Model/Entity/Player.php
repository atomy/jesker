<?php

namespace IOGames\Jesker\Model\Entity;

/**
 * Class Player.
 *
 * Entity class used in rcon *status* response.
 */
class Player
{
    public function __construct(public int $steamId, public string $name, public int $ping = 30, public string $connected = '97.588s', public string $ip = '127.0.0.1')
    {
    }
}