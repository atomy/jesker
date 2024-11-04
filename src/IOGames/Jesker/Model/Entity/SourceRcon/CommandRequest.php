<?php

namespace IOGames\Jesker\Model\Entity\SourceRcon;

use IOGames\Jesker\Model\Entity\AbstractRequest;

class CommandRequest extends AbstractRequest
{
    /**
     * @var string
     */
    public string $command;

    /**
     * @var string
     */
    public string $receivedPacketId;
}