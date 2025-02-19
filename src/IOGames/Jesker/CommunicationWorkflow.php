<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\AbstractRequest;
use IOGames\Jesker\Model\Entity\AbstractResponse;
use IOGames\Jesker\Model\Entity\HeaderimageRequest;
use IOGames\Jesker\Model\Entity\SourceRcon\ChatResponse;
use IOGames\Jesker\Model\Entity\SourceRcon\CommandRequest;
use IOGames\Jesker\Model\Entity\SourceRcon\PasswordRequest;
use IOGames\Jesker\Model\Entity\SourceRcon\PasswordResponse;
use IOGames\Jesker\Model\Entity\SourceRcon\RawResponse;
use IOGames\Jesker\Model\Entity\SourceRcon\StatusResponse;
use IOGames\Jesker\Model\Entity\StatusRequest;
use IOGames\Jesker\Model\Entity\Websocket\WebsocketAuthRequest;
use IOGames\Jesker\Service\Data\Database;
use IOGames\Jesker\Model\Entity\WebRcon\PasswordRequest as WebSocketPasswordRequest;
use IOGames\Jesker\Model\Entity\WebRcon\PasswordResponse as WebSocketPasswordResponse;
use IOGames\Jesker\Model\Entity\WebRcon\StatusResponse as WebRconStatusResponse;
use IOGames\Jesker\Model\Entity\WebRcon\HeaderimageResponse as WebRconHeaderimageResponse;

/**
 * Class CommunicationWorkflow.
 *
 * Handle communication workflow state and decide based on that state the correct responses.
 */
class CommunicationWorkflow
{
    /**
     * @var bool
     */
    public bool $isAuthed = false;

    /**
     * @var true
     */
    public bool $errorWrongPassword;

    /**
     * @param AbstractRequest|null $requestEntity
     * @return AbstractResponse[]
     */
    public function getResponse(AbstractRequest $requestEntity = null): array
    {
        if ($requestEntity instanceof StatusRequest) {
            $statusResponse = new WebRconStatusResponse($requestEntity->identifier);

            if (getenv('FAKE_PLAYERS')) {
                LobbyBuilder::getInstance()->createLobbyByJson(Helper::validateAndGetEnv('FAKE_PLAYERS'));
            } else {
                LobbyBuilder::getInstance()->createLobby(Helper::validateAndGetEnv('FAKE_PLAYER_COUNT'));
            }

            $statusResponse->players = LobbyBuilder::getInstance()->getPlayers();
            $statusResponse->hostname = Helper::validateAndGetEnv('SERVER_NAME');

            return [$statusResponse];
        } elseif ($requestEntity instanceof PasswordRequest) {
            return [new PasswordResponse()];
        } elseif ($requestEntity instanceof WebsocketAuthRequest) {
            return [];
        } elseif ($requestEntity instanceof HeaderimageRequest) {
            return [new WebRconHeaderimageResponse($requestEntity->identifier)];
        } elseif ($requestEntity instanceof WebSocketPasswordRequest) {
            return [new WebSocketPasswordResponse($this->errorWrongPassword, $requestEntity->headers)];
        } elseif ($requestEntity instanceof CommandRequest) {
            if (!$this->isAuthed) {
                throw new \RuntimeException('Tried to send command without being authed!');
            }

            if ($requestEntity->command == 'status') {
                $statusResponse = new StatusResponse();

                if ($requestEntity->receivedPacketId) {
                    $statusResponse->packetId = $requestEntity->receivedPacketId;
                }

                if (getenv('FAKE_PLAYERS')) {
                    LobbyBuilder::getInstance()->createLobbyByJson(Helper::validateAndGetEnv('FAKE_PLAYERS'));
                } else {
                    LobbyBuilder::getInstance()->createLobby(Helper::validateAndGetEnv('FAKE_PLAYER_COUNT'));
                }

                $statusResponse->players = LobbyBuilder::getInstance()->getPlayers();
                $statusResponse->hostname = Helper::validateAndGetEnv('SERVER_NAME');

                return [$statusResponse];
            } elseif ($requestEntity->command == 'test') {
                $database = new Database();
                $database->init();
                $chatCollection = $database->getChat();

                $rawCommandResponse = new RawResponse();
                $rawCommandResponse->setPacketId($requestEntity->receivedPacketId);
                //$rawCommandResponse->setRawContent('');

                $chatResponse = new ChatResponse();
                $chatResponse->setSender('atomy[1330256/765611979605255]');
                $chatResponse->setMessage('hello world!');

                return [$rawCommandResponse, $chatResponse];
            }

            throw new \RuntimeException(sprintf("Unable to find response for command '%s'", $requestEntity->command));
        }

        throw new \RuntimeException(sprintf("Unable to find response for request '%s'", $requestEntity ? get_class($requestEntity) : 'null'));
    }
}