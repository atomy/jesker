<?php

namespace IOGames\Jesker\Model\Entity;

use IOGames\Jesker\Model\Entity\AbstractResponse;
use IOGames\Jesker\Model\Entity\Player;
use IOGames\Jesker\Service\PacketHelper;

/**
 * Class StatusRequest.
 */
class StatusRequest extends AbstractRequest
{
    public function __construct(public int $identifier)
    {
    }
}
