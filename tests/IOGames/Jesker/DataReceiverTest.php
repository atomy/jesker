<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\SourceRcon\PasswordRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class DataReceiverTest
 */
class DataReceiverTest extends TestCase
{
    /**
     * @var DataReceiver
     */
    protected static $dataReceiver;

    /**
     * Prepare.
     */
    public function setUp()
    {
        parent::setUp();

        if (!self::$dataReceiver) {
            self::$dataReceiver = new DataReceiver();
        }
    }

    /**
     * @param $requestEntityClassName
     * @param $unpackedPacket
     * @param $responseEntityClassName
     * @param $responseData
     * @dataProvider dataProvider
     */
    public function testReceive($requestEntityClassName, $unpackedPacket, $responseEntityClassName, $responseData)
    {
        $requestEntity = self::$dataReceiver->receive($unpackedPacket);
        $responseEntity = self::$dataReceiver->getResponse();

        self::assertInstanceOf($requestEntityClassName, $requestEntity);

        if ($requestEntity instanceof PasswordRequest) {
            self::assertEquals('test10', $requestEntity->rconPassword);
        }

        self::assertInstanceOf($responseEntityClassName, $responseEntity);
        self::assertEquals($responseData, $responseEntity->getData());
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                'IOGames\Jesker\Model\Entity\SourceRcon\PasswordRequest',
                ['13', '00', '00', '00', 'b0', '04', '00', '00', '03', '00', '00', '00', '61', '64', '6d', '69', '6e', '31', '33', '33', '37', '00', '00'],
                'IOGames\Jesker\Model\Entity\SourceRcon\PasswordResponse',
                ['0a000000b0040000000000000000', '0a000000b0040000020000000000'],
            ],
            [
                'IOGames\Jesker\Model\Entity\SourceRcon\CommandRequest',
                ['10','00','00','00','e8','03','00','00','02','00','00','00','73','74','61','74','75','73','00','00'],
                'IOGames\Jesker\Model\Entity\SourceRcon\StatusResponse',
                [
                    '2a00000000000000040000005b52434f4e5d5b39352e39302e3231342e323a32363032365d207374617475730000',
                    /* 'e2000000e803000000000000686f73746e616d653a2052636f6e204c65676163792032383032360a76657273696f6e203a2031393238207365637572652028736563757265206d6f646520656e61626c65642c20636f6e6e656374656420746f20537465616d33290a6d617020202020203a2050726f6365647572616c204d61700a706c6179657273203a20302028353030206d6178292028302071756575656429202830206a6f696e696e67290a0a6964206e616d652070696e6720636f6e6e65637465642061646472206f776e65722076696f6c6174696f6e206b69636b73200d0a0000' */
                    '54010000e803000000000000686f73746e616d653a2052636f6e204c65676163792032383032360a76657273696f6e203a2031393238207365637572652028736563757265206d6f646520656e61626c65642c20636f6e6e656374656420746f20537465616d33290a6d617020202020203a2050726f6365647572616c204d61700a706c6179657273203a20312028353030206d6178292028302071756575656429202830206a6f696e696e67290a0a6964202020202020202020202020202020206e616d652020202070696e6720636f6e6e6563746564206164647220202020202020202020202020206f776e65722076696f6c6174696f6e206b69636b73200d0a3736353631313937393630353235353030202261746f6d792220333020202039372e3538387320202039352e39302e3231342e323a363335363520202020202020302e30202020202020203020202020200d0a0000'
                ]
            ]
        ];
    }
}