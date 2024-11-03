<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\SourceRcon\CommandRequest;
use IOGames\Jesker\Model\Entity\SourceRcon\PasswordRequest;

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
     * @return Model\Entity\SourceRcon\AbstractSourceRcon
     */
    public function guess()
    {
        $guessSlice = array_slice($this->unpackedPacketData, 0, 12);
        $remainingData = array_slice($this->unpackedPacketData, 12);

        if ($this->packetMatcher->setRule(PacketConfig::$passwordRequest)->doesMatch($guessSlice)) {
            $passwordRequest = new PasswordRequest();

            $reminingDataAsString = $this->decodeData($remainingData);
            $passwordRequest->rconPassword = $reminingDataAsString;

            return $passwordRequest;
        } elseif ($this->packetMatcher->setRule(PacketConfig::$commandRequest)->doesMatch($guessSlice)) {
            $sentPacketId = $this->packetMatcher->setRule(PacketConfig::$commandRequest)->doesMatch($guessSlice);
            $commandRequest = new CommandRequest();
            $commandRequest->receivedPacketId = $sentPacketId;

            $reminingDataAsString = $this->decodeData($remainingData);
            $commandRequest->command = $reminingDataAsString;

            return $commandRequest;
        } elseif ($this->packetMatcher->setRule(PacketConfig::$webRconRequest)->doesMatch($guessSlice)) {
            return null;
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