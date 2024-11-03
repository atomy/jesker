<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Collection\Database\Chat;
use IOGames\Jesker\Model\Entity\SourceRcon\ChatResponse;
use IOGames\Jesker\Model\Entity\SourceRcon\RawResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class ChatResponseTest
 */
class ChatResponseTest extends TestCase
{
    /**
     *
     */
    public function testRawResponse()
    {
        $rawResponse = new RawResponse();
        $rawResponse->setRawContent('Command not found');
        $rawResponse->setPacketId('65');

        self::assertEquals('436f6d6d616e64206e6f7420666f756e64', $rawResponse->getEncodedContent());
        self::assertEquals('1b', $rawResponse->getHexSize());
        self::assertEquals('2800000000000000040000005b52434f4e5d5b39352e39302e3231342e323a35303534345d20746573740000', $rawResponse->getData()[0]);
        self::assertEquals('1b0000006503000000000000436f6d6d616e64206e6f7420666f756e640000', $rawResponse->getData()[1]);
    }

    /**
     *
     */
    public function testChatResponse()
    {
        $rawResponse = new ChatResponse();
        $rawResponse->setSender('pew pew [33]');
        $rawResponse->setMessage('HELLO WORLD');

        self::assertEquals('5b434841545d2070657720706577205b33335d203a2048454c4c4f20574f524c44', $rawResponse->getEncodedContent());
        self::assertEquals('2b', $rawResponse->getHexSize());
        self::assertEquals('2800000000000000040000005b52434f4e5d5b39352e39302e3231342e323a35303534345d20746573740000', $rawResponse->getData()[0]);
        self::assertEquals('2b00000003000000000000436f6d6d616e64206e6f7420666f756e64440000', $rawResponse->getData()[1]);
    }
}