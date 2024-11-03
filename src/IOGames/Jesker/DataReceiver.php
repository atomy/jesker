<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\SourceRcon\CommandRequest;
use IOGames\Jesker\Model\Entity\SourceRcon\PasswordRequest;

/**
 * Class DataReceiver
 */
class DataReceiver
{
    /**
     * @var PacketGuesser
     */
    protected $packetGuesser;

    /**
     * @var Model\Entity\SourceRcon\AbstractSourceRcon
     */
    protected $requestEntity;

    /**
     * @var
     */
    protected $responseEntities;

    /**
     * DataReceiver constructor.
     */
    public function __construct()
    {
        $this->packetGuesser = new PacketGuesser();
        $this->communicationWorkflow = new CommunicationWorkflow();
    }

    /**
     * @param $unpackedData
     * @return Model\Entity\SourceRcon\AbstractSourceRcon
     */
    public function receive($unpackedData)
    {
        // reset response
        $this->responseEntities = [];

        $this->packetGuesser->setUnpackedPacketData($unpackedData);

        try {
            $this->requestEntity = $this->packetGuesser->guess();
            $this->postProcessReceive();
        } catch (\Exception $e) {
            error_log($e); // %TODO, logger
        }

        return $this->requestEntity;
    }

    /**
     * @return Model\Entity\SourceRcon\AbstractSourceRcon[]
     */
    public function getResponse()
    {
        try {
            $this->determineResponse();
        } catch (\Exception $e) {
            error_log($e);
        }

        return $this->responseEntities;
    }

    /**
     *
     */
    public function determineResponse()
    {
        if (empty($this->responseEntities)) {
            $this->responseEntities = $this->communicationWorkflow->getResponse($this->requestEntity);
        }
    }

    /**
     *
     */
    private function postProcessReceive()
    {
        if ($this->requestEntity instanceof PasswordRequest) {
            if ($this->requestEntity->rconPassword == 'pw10') {
                echo 'SUCCESSFULLY AUTHED' . PHP_EOL;
                $this->communicationWorkflow->isAuthed = true;
            } else {
                throw new \RuntimeException("Received auth packet with wrong password: '%s'");
            }
        } elseif ($this->requestEntity instanceof CommandRequest) {
            echo 'RECEIVED COMMAND: ' . $this->requestEntity->command . PHP_EOL;
        }
    }
}