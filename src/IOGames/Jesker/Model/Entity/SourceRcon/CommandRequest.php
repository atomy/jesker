<?php

namespace IOGames\Jesker\Model\Entity\SourceRcon;

class CommandRequest extends AbstractSourceRcon
{
    /**
     * @var
     */
    public $command;

    /**
     * @var int
     */
    public $receivedPacketId;
}