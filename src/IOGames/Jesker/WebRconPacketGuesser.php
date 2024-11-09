<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\StatusRequest;

class WebRconPacketGuesser
{
    /**
     * @var string
     */
    protected string $data;

    /**
     * @param string $data
     * @return WebRconPacketGuesser
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return StatusRequest
     */
    public function guess(): StatusRequest
    {
        $jsonData = json_decode($this->data, true);

        if (isset($jsonData['Name']) && $jsonData['Name'] === 'WebRcon' && isset($jsonData['Message']) && $jsonData['Message'] === 'status') {
            return new StatusRequest($jsonData['Identifier']);
        }

        throw new \RuntimeException(sprintf(
            "Unable to guess packet type using data '%s'!",
            $this->data
        ));
    }
}