<?php

namespace IOGames\Jesker\Service\Data;

use IOGames\Jesker\Model\Collection\Database\Chat;
use IOGames\Jesker\Model\Collection\Database\Player;

/**
 * Class Database
 */
class Database
{
    /**
     * @var \mysqli
     */
    protected $mysqli;

    /**
     * @return $this
     */
    public function init()
    {
        $this->mysqli = new \mysqli('localhost', 'jesker', 'jesker', 'jesker');

        if ($this->mysqli->connect_errno) {
            throw new \RuntimeException("Failed to connect to mysql server: " . $this->mysqli->connect_error);
        }

        return $this;
    }

    /**
     * @return Player
     */
    public function getPlayers()
    {
        $results = $this->mysqli->query("SELECT * FROM players;")->fetch_all(MYSQLI_ASSOC);
        $playerCollection = new Player();

        foreach ($results as $result) {
            $playerEntity = new \IOGames\Jesker\Model\Entity\Database\Player();
            $playerEntity->id = $result['id'];
            $playerEntity->nickname = $result['nickname'];
            $playerEntity->steam64 = $result['steam64'];
            $playerEntity->ping = $result['ping'];

            $playerCollection->playerEntities[] = $playerEntity;
        }

        return $playerCollection;
    }

    /**
     * @return Chat
     */
    public function getChat()
    {
        $results = $this->mysqli->query("SELECT * FROM chat WHERE `sent` = 0;")->fetch_all(MYSQLI_ASSOC);
        $this->mysqli->query("UPDATE `chat` SET `sent` = 1;");

        $chatCollection = new Chat();

        foreach ($results as $result) {
            $chatEntity = new \IOGames\Jesker\Model\Entity\Database\Chat();
            $chatEntity->id = $result['id'];
            $chatEntity->content = $result['content'];
            $chatEntity->sender = $result['sender'];
            $chatEntity->sent = $result['sent'];

            $chatCollection->chatEntities[] = $chatEntity;
        }

        return $chatCollection;
    }
}