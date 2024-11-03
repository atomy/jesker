<?php

namespace IOGames\Jesker;

/**
 * Class DataReceiver
 */
class DataUnpacker
{
    /**
     * Unpack raw data containing crypto chars into their HEX representative.
     *
     * @param $rawData string
     * @return string
     */
    public function unpack($rawData)
    {
        $unpackedData = unpack('H*', $rawData);

        echo 'UNPACKED DATA: ' . $unpackedData[1] . PHP_EOL;

        $unpackedData = reset($unpackedData);
        $resultToken = array();

        for ($i = 0; $i < strlen($unpackedData); $i+=2) {
            $resultToken[] = $unpackedData[$i] . $unpackedData[$i+1];
        }

        return $resultToken;
    }
}