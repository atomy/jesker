<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\SourceRcon\CommandRequest;
use IOGames\Jesker\Model\Entity\SourceRcon\PasswordRequest;
use IOGames\Jesker\Model\Entity\Websocket\WebsocketAuthRequest;

class PacketGuesser
{
    const SOURCE_RCON_PASSWORD_AUTH = 'SOURCE_RCON_PASSWORD_AUTH';

    /**
     * @var array
     */
    protected $unpackedPacketData;

    /**
     * @var PacketMatcher
     */
    protected $packetMatcher;

    /**
     * PacketGuesser constructor.
     */
    public function __construct()
    {
        $this->packetMatcher = new PacketMatcher();
    }

    /**
     * @param array $data
     */
    public function setUnpackedPacketData(array $data)
    {
        $this->unpackedPacketData = $data;
    }

    /**
     * @return Model\Entity\SourceRcon\AbstractSourceRcon|WebsocketAuthRequest
     */
    public function guess(): PasswordRequest|Model\Entity\SourceRcon\AbstractSourceRcon|CommandRequest|WebsocketAuthRequest|null
    {
        $guessSlice = array_slice($this->unpackedPacketData, 0, 12);
        $remainingData = array_slice($this->unpackedPacketData, 12);
        $decodedString = implode('', array_map('hex2bin', $this->unpackedPacketData));

        if (preg_match('/GET \/(\S+)\sHTTP\/1\.1/', $decodedString, $matches)) {
            $inputPassword = $matches[1];

            return new WebsocketAuthRequest($inputPassword);
        } elseif ($this->packetMatcher->setRule(PacketConfig::$passwordRequest)->doesMatch($guessSlice)) {
            $passwordRequest = new PasswordRequest();

            $remainingDataAsString = $this->decodeData($remainingData);
            $passwordRequest->rconPassword = $remainingDataAsString;

            return $passwordRequest;
        } elseif ($this->packetMatcher->setRule(PacketConfig::$commandRequest)->doesMatch($guessSlice)) {
            $sentPacketId = $this->packetMatcher->setRule(PacketConfig::$commandRequest)->doesMatch($guessSlice);
            $commandRequest = new CommandRequest();
            $commandRequest->receivedPacketId = $sentPacketId;

            $remainingDataAsString = $this->decodeData($remainingData);
            $commandRequest->command = $remainingDataAsString;

            return $commandRequest;
        }

        throw new \RuntimeException(sprintf(
            "Unable to guess packet type using slice '%s'!",
            implode(',', $guessSlice)
        ));
    }

    /**
     * @param array $remainingData
     * @return string
     */
    private function decodeData(array $remainingData)
    {
        $result = '';

        foreach ($remainingData as $token) {
            if ($token === '00') {
                break;
            }

            $result .= chr(hexdec($token));
        }

        return $result;
    }
}