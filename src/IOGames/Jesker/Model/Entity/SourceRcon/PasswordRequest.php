<?php

namespace IOGames\Jesker\Model\Entity\SourceRcon;

use IOGames\Jesker\Model\Entity\AbstractRequest;

class PasswordRequest extends AbstractRequest
{
    /**
     * @var string
     */
    public string $rconPassword;
}