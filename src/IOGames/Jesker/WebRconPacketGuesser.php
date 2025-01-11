<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\AbstractRequest;
use IOGames\Jesker\Model\Entity\HeaderimageRequest;
use IOGames\Jesker\Model\Entity\StatusRequest;

/**
 * Class WebRconPacketGuesser.
 */
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
     * @return AbstractRequest
     */
    public function guess(): AbstractRequest
    {
        $jsonData = json_decode($this->data, true);

        if (isset($jsonData['Name']) && $jsonData['Name'] === 'WebRcon' && isset($jsonData['Message']) && $jsonData['Message'] === 'status') {
            return new StatusRequest($jsonData['Identifier']);
        }

        if (isset($jsonData['Name']) && $jsonData['Name'] === 'WebRcon' && isset($jsonData['Message']) && $jsonData['Message'] === 'server.headerimage') {
            return new HeaderimageRequest($jsonData['Identifier']);
        }

        throw new \RuntimeException(sprintf(
            "Unable to guess packet type using data '%s'!",
            $this->data
        ));
    }
}