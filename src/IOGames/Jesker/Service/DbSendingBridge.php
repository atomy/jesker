<?php

namespace IOGames\Jesker\Service;

use IOGames\Jesker\Model\Entity\SourceRcon\ChatResponse;
use IOGames\Jesker\Service\Data\Database;
use IOGames\Jesker\Service\Rcon\Mapper;
use Workerman\Connection\TcpConnection;

/**
 * Class DbSendingBridge
 */
class DbSendingBridge
{
    /**
     * @var TcpConnection
     */
    protected $connection;

    /**
     * DbSendingBridge constructor.
     *
     * @param TcpConnection $connection
     */
    public function __construct(TcpConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     *
     */
    private function sendDbChat()
    {
        $database = new Database();
        $database->init();
        $chatCollection = $database->getChat();

        foreach ($chatCollection->chatEntities as $chatEntity) {
            $chatResponse = new ChatResponse();
            $chatResponse->content = $chatEntity->content;
            $chatResponse->sender = $chatEntity->sender;

            foreach ($chatResponse->getData() as $data) {
                $this->connection->send($data);
                echo 'SENDING: ' . $data . PHP_EOL;
            }
        }
    }
}