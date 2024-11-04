<?php

namespace IOGames\Jesker\Model\Entity;

abstract class AbstractResponse
{
    /**
     * Raw response content.
     *
     * @var string
     */
    public string $rawContent;

    /**
     * Response packet id.
     *
     * @var string
     */
    public string $packetId;
}