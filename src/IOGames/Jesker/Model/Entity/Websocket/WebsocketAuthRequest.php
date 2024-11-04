<?php

namespace IOGames\Jesker\Model\Entity\Websocket;

use IOGames\Jesker\Model\Entity\AbstractRequest;

class WebsocketAuthRequest extends AbstractRequest
{
    public function __construct(protected string $password)
    {
    }
}