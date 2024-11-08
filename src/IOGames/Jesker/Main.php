<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\SourceRcon\AbstractSourceRcon;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;

class Main
{
    /**
     * Start the whole thing.
     *
     * @return void
     */
    public function run(): void
    {
        Worker::runAll();
    }

    /**
     * Create worker based on RCON_WEB env.
     *
     * @return void
     */
    public function createWorker(): void
    {
        $rconWeb = (bool) getenv('RCON_WEB');

        if ($rconWeb) {
            $this->createWebRconWorker();
        } else {
            $this->createSourceRconWorker();
        }
    }

    /**
     * Create a worker server based on source-rcon.
     *
     * @return void
     */
    protected function createSourceRconWorker(): void
    {
        echo __METHOD__ . PHP_EOL;
        echo sprintf("Creating source-rcon-server with address: '%s'", sprintf("tcp://0.0.0.0:%d", getenv('SERVER_PORT'))) . PHP_EOL;

        $tcpWorker = new Worker(sprintf("tcp://0.0.0.0:%d", getenv('SERVER_PORT')));
        $tcpWorker->count = 1;
        $tcpWorker->onConnect = function(TcpConnection $connection) {
            echo 'New connection!' . PHP_EOL;
        };

        $dataUnpacker = new DataUnpacker();
        $dataReceiver = new DataReceiver();

        $tcpWorker->onMessage = function(TcpConnection $connection, $data) use($dataReceiver, $dataUnpacker) {
            $unpackedData = $dataUnpacker->unpack($data);
            $decodedString = implode('', array_map('hex2bin', $unpackedData));

            echo 'RECEIVING: ' . PHP_EOL;
            echo implode(',', $unpackedData);
            echo PHP_EOL;
            echo ' ASCII: ' . PHP_EOL;
            echo $decodedString . PHP_EOL;
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
    }

    /**
     * Create rcon server based on webrcon protocol.
     *
     * @return void
     */
    protected function createWebRconWorker(): void
    {
        echo __METHOD__ . PHP_EOL;
        echo sprintf("Creating web-rcon-server with address: '%s'", sprintf("tcp://0.0.0.0:%d", getenv('SERVER_PORT'))) . PHP_EOL;

        $tcpWorker = new Worker(sprintf("tcp://0.0.0.0:%d", getenv('SERVER_PORT')));
        $tcpWorker->count = 1;
        $tcpWorker->onConnect = function(TcpConnection $connection) {
            echo 'New connection!' . PHP_EOL;
        };

        $webRconDataReceiver = new WebRconDataReceiver();

        $tcpWorker->onMessage = function(TcpConnection $connection, $data) use($webRconDataReceiver) {
            echo 'RECEIVING: ' . PHP_EOL;
            var_dump($data);die(__METHOD__ . ':' . __LINE__);

            $webRconDataReceiver->receive($data);
            $responseEntities = $webRconDataReceiver->getResponse();
var_dump($responseEntities);die(__METHOD__ . ':' . __LINE__);
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
    }
}