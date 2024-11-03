<?php

namespace IOGames\Jesker\Model\Entity\SourceRcon;

/**
 * Class ChatResponse
 */
class ChatResponse extends RawResponse
{
    /**
     * @var string
     */
    protected $sender;

    /**
     * @var
     */
    protected $message;

    /**
     * @return array
     */
    public function getData()
    {
        return [
            sprintf('%s0000000000000004000000%s0000', $this->getHexSize(), $this->getEncodedContent())
        ];
    }

    /**
     * @param $sender
     */
    public function setSender($sender)
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