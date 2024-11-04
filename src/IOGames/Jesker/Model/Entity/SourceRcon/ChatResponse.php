<?php

namespace IOGames\Jesker\Model\Entity\SourceRcon;

use IOGames\Jesker\Model\Entity\AbstractResponse;

/**
 * Class ChatResponse
 */
class ChatResponse extends AbstractResponse
{
    /**
     * @var string
     */
    protected string $sender;

    /**
     * @var string
     */
    protected string $message;

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            sprintf('%s0000000000000004000000%s0000', $this->getHexSize(), $this->getEncodedContent())
        ];
    }

    /**
     * @param $sender
     */
    public function setSender($sender): void
    {
        $this->sender = $sender;
        $this->rawContent = sprintf('[CHAT] %s : %s', $this->sender, $this->message);
    }

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
        $this->rawContent = sprintf('[CHAT] %s : %s', $this->sender, $this->message);
    }
}