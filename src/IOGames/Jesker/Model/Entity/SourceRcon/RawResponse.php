<?php

namespace IOGames\Jesker\Model\Entity\SourceRcon;

use IOGames\Jesker\Model\Entity\AbstractResponse;
use IOGames\Jesker\Service\PacketHelper;

/**
 * Class RawResponse
 */
class RawResponse extends AbstractResponse
{
    /**
     * @var string
     */
    protected string $packetId;

    /**
     * @return array
     */
    public function getData(): array
    {
        /**
         * RECEIVED COMMAND: test
        SENDING: 2a0000000000000004000000746573740000
        SENDING: 3f00000000000000040000005b434841545d20203a200000
         */
        //echo 'size: ' . $this->getPacketSize().  PHP_EOL;
        return [
            '2800000000000000040000005b52434f4e5d5b39352e39302e3231342e323a35303534345d20746573740000',
            sprintf('%s000000000000%s03000000%s0000', $this->getHexSize(), $this->packetId, $this->getEncodedContent()),
        ];
    }

    /**
     * @param $rawContent
     */
    public function setRawContent($rawContent): void
    {
        $this->rawContent = $rawContent;
    }

    /**
     * @param $packetId
     * @return RawResponse
     */
    public function setPacketId($packetId): self
    {
        $this->packetId = $packetId;

        return $this;
    }

    /**
     * @return string
     */
    public function getEncodedContent(): string
    {
        return PacketHelper::encode($this->rawContent);
    }

    /**
     * @return string
     */
    public function getHexSize(): string
    {
        $hex = dechex(strlen($this->rawContent) + 10);

        if (strlen($hex) === 1) {
            return '0' . $hex;
        }

        return $hex;
    }
}