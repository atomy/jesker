<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\AbstractResponse;
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
    protected PacketGuesser $packetGuesser;

    /**
     * @var Model\Entity\AbstractRequest
     */
    protected Model\Entity\AbstractRequest $requestEntity;

    /**
     * @var AbstractResponse[]
     */
    protected array $responseEntities;

    /**
     * @var CommunicationWorkflow
     */
    protected CommunicationWorkflow $communicationWorkflow;

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
     * @return Model\Entity\AbstractRequest|null
     */
    public function receive($unpackedData): ?Model\Entity\AbstractRequest
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
     * @return Model\Entity\AbstractResponse[]
     */
    public function getResponse(): array
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
    public function determineResponse(): void
    {
        if (empty($this->responseEntities)) {
            $this->responseEntities = $this->communicationWorkflow->getResponse($this->requestEntity);
        }
    }

    /**
     *
     */
    private function postProcessReceive(): void
    {
        if ($this->requestEntity instanceof PasswordRequest) {
            echo sprintf("Validating input password '%s' to preset password '%s'\n", getenv('RCON_PASSWORD'), $this->requestEntity->rconPassword);

            if ($this->requestEntity->rconPassword == getenv('RCON_PASSWORD')) {
                echo 'SUCCESSFULLY AUTHED' . PHP_EOL;
                $this->communicationWorkflow->isAuthed = true;
            } else {
                throw new \RuntimeException(sprintf("Received auth packet with wrong password: '%s' (expected: '%s')", $this->requestEntity->rconPassword, getenv('RCON_PASSWORD')));
            }
        } elseif ($this->requestEntity instanceof CommandRequest) {
            echo 'RECEIVED COMMAND: ' . $this->requestEntity->command . PHP_EOL;
        }
    }
}