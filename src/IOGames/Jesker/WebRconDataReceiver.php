<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\AbstractResponse;
use IOGames\Jesker\Model\Entity\SourceRcon\CommandRequest;
use IOGames\Jesker\Model\Entity\SourceRcon\PasswordRequest as SourceRconPasswordRequest;
use IOGames\Jesker\Model\Entity\WebRcon\PasswordRequest as WebRconPasswordRequest;

/**
 * Class WebRconDataReceiver
 */
class WebRconDataReceiver
{
    /**
     * @var WebRconPacketGuesser
     */
    protected WebRconPacketGuesser $webPacketPacketGuesser;

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
        $this->webPacketPacketGuesser = new WebRconPacketGuesser();
        $this->communicationWorkflow = new CommunicationWorkflow();
    }

    /**
     * Receive input data.
     *
     * @param string $inputData
     * @return Model\Entity\AbstractRequest|null
     */
    public function receive(string $inputData): ?Model\Entity\AbstractRequest
    {
        // reset response
        $this->responseEntities = [];

        $this->webPacketPacketGuesser->setData($inputData);

        try {
            $this->requestEntity = $this->webPacketPacketGuesser->guess();
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
        if ($this->requestEntity instanceof SourceRconPasswordRequest) {
            echo sprintf("Validating input password '%s' to preset password '%s'\n", Helper::validateAndGetEnv('RCON_PASSWORD'), $this->requestEntity->rconPassword);

            if ($this->requestEntity->rconPassword == Helper::validateAndGetEnv('RCON_PASSWORD')) {
                echo 'SUCCESSFULLY AUTHED' . PHP_EOL;
                $this->communicationWorkflow->isAuthed = true;
            } else {
                throw new \RuntimeException(sprintf("Received auth packet with wrong password: '%s' (expected: '%s')", $this->requestEntity->rconPassword, Helper::validateAndGetEnv('RCON_PASSWORD')));
            }
        } elseif ($this->requestEntity instanceof CommandRequest) {
            echo 'RECEIVED COMMAND: ' . $this->requestEntity->command . PHP_EOL;
        } elseif ($this->requestEntity instanceof WebRconPasswordRequest) {
            echo sprintf("Validating input password '%s' to preset password '%s'\n", Helper::validateAndGetEnv('RCON_PASSWORD'), $this->requestEntity->rconPassword);

            if ($this->requestEntity->rconPassword == Helper::validateAndGetEnv('RCON_PASSWORD')) {
                echo 'SUCCESSFULLY AUTHED' . PHP_EOL;
                $this->communicationWorkflow->isAuthed = true;
                $this->communicationWorkflow->errorWrongPassword = false;
            } else {
                $this->communicationWorkflow->errorWrongPassword = true;
                echo sprintf("Received auth packet with wrong password: '%s' (expected: '%s')", $this->requestEntity->rconPassword, Helper::validateAndGetEnv('RCON_PASSWORD')) . PHP_EOL;
            }
        }
    }
}