<?php

namespace IOGames\Jesker\Service;

use IOGames\Jesker\Model\Entity\SourceRcon\ChatResponse;
use IOGames\Jesker\Service\Data\Database;
use IOGames\Jesker\Service\Rcon\Mapper;
use Workerman\Connection\TcpConnection;

/**
 * Class PacketHelper
 */
class PacketHelper
{
    /**
     * @param $inData
     * @return string
     */
    public static function encode($inData)
    {
        $unpacked = unpack('H*', $inData);
        return array_shift($unpacked);
    }
}