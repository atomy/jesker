<?php

namespace IOGames\Jesker\Model\Entity\WebRcon;

use IOGames\Jesker\Model\Entity\AbstractRequest;

/**
 * Class PasswordRequest.
 */
class PasswordRequest extends AbstractRequest
{
    /**
     * @param string $rconPassword
     */
    public function __construct(public string $rconPassword, public array $headers)
    {
    }

    public function getSecWebSocketKey()
    {
        return $this->headers['Sec-WebSocket-Key'] ?? null;
    }

    public function getHost()
    {
        return $this->headers['Host'] ?? null;
    }

    public function getOrigin()
    {
        return $this->headers['Origin'] ?? null;
    }
}