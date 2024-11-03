<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\SourceRcon\AbstractSourceRcon;
use IOGames\Jesker\Service\DbSendingBridge;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;

class Main
{
    public function work()
    {
        $tcpWorker = new Worker("tcp://0.0.0.0:28026");
        $tcpWorker->count = 1;
        $tcpWorker->onConnect = function(TcpConnection $connection) {
            echo 'New connection!' . PHP_EOL;
        };

        $dataUnpacker = new DataUnpacker();
        $dataReceiver = new DataReceiver();

        $tcpWorker->onMessage = function(TcpConnection $connection, $data) use($dataReceiver, $dataUnpacker) {
            $unpackedData = $dataUnpacker->unpack($data);

            echo 'RECEIVING: ' . PHP_EOL;
            echo implode(',', $unpackedData);
            echo PHP_EOL;
            echo PHP_EOL;

            $dataReceiver->receive($unpackedData);
            $responseEntities = $dataReceiver->getResponse();

            if (count($responseEntities) > 0) {
                /** @var AbstractSourceRcon $responseEntity */
                foreach ($responseEntities as $responseEntity) {
                    $sendData = $responseEntity->getData();

                    foreach ($sendData as $sendDataEntry) {
                        echo 'SENDING: ' . $sendDataEntry . PHP_EOL;
                        $connection->send(pack('H*', $sendDataEntry));
                    }
                }
            }
        };

        $tcpWorker->onWorkerReload = function(Worker $worker) {
            echo 'RELOAD: ' . PHP_EOL . PHP_EOL;
            var_dump($worker);
        };

        $tcpWorker->onClose = function($connection) {
            echo "Connection closed\n";
        };

        Worker::runAll();
    }
}