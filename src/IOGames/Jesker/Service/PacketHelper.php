<?php

namespace IOGames\Jesker\Service;

use IOGames\Jesker\Model\Entity\SourceRcon\ChatResponse;
use IOGames\Jesker\Service\Data\Database;
use IOGames\Jesker\Service\Rcon\Mapper;
use Workerman\Connection\TcpConnection;

/**
 * Class PacketHelper
 */
class PacketHelper
{
    /**
     * @param $inData
     * @return string
     */
    public static function encode($inData)
    {
        $unpacked = unpack('H*', $inData);
        return array_shift($unpacked);
    }

    /**
     * @param $number
     * @return string
     */
    public static function intToLittleEndianHex($number): string
    {
        // Pack the integer as a 32-bit little-endian unsigned integer
        $binaryData = pack('V', $number); // 'V' is for unsigned long (32-bit) in little-endian
        // Convert binary data to hexadecimal
        $hex = bin2hex($binaryData);

        return strtoupper($hex); // Return the hex in uppercase for consistency
    }

    /**
     * @param $number
     * @return string
     */
    public static function intToBigEndianHex($number): string
    {
        // Pack the integer as a 32-bit big-endian unsigned integer
        $binaryData = pack('N', $number); // 'N' is for unsigned long (32-bit) in big-endian
        // Convert binary data to hexadecimal
        $hex = bin2hex($binaryData);
        return strtoupper($hex); // Return the hex in uppercase for consistency
    }

    public static function calculatePacketSize($hexContent)
    {
        // Step 1: Convert the hex string to binary data
        $binaryData = hex2bin($hexContent);

        // Step 2: Calculate the length of the binary data
        $length = strlen($binaryData);

        // Step 3: Convert the length to little-endian hexadecimal representation
        $littleEndianSize = pack('V', $length); // 'V' for unsigned long in little-endian
        $littleEndianHex = strtoupper(bin2hex($littleEndianSize)); // Convert to hex and uppercase

        return $littleEndianHex;
    }
}