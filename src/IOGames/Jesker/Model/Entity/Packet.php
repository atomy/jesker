<?php

namespace IOGames\Jesker\Model\Entity;

class Packet
{
    /**
     * @var string
     */
    protected $rawData;

    /**
     * @var
     */
    protected $resultToken = [];

    /**
     * Packet constructor.
     * @param $rawData
     */
    public function __construct($rawData)
    {
        $this->rawData = $rawData;
    }
}