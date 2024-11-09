<?php

namespace IOGames\Jesker\Model\Entity\WebRcon;

use IOGames\Jesker\Model\Entity\AbstractRequest;
use IOGames\Jesker\Model\Entity\Player;

/**
 * Class StatusResponse.
 */
class StatusResponse extends AbstractRequest
{
    /**
     * Packet id.
     *
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $joiningPlayers = 0;

    /**
     * @var int
     */
    public int $queuedPlayers = 0;

    /**
     * @var int
     */
    public int $maxPlayers = 499;

    /**
     * @var Player[]
     */
    public array $players;

    /**
     * @var string
     */
    public string $mapName = 'Procedural Map';

    /**
     * @var string
     */
    public string $hostname = '2nd server';

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        // Define the server status data
        $serverStatus = [
            'hostname' => $this->hostname,
            'version' => '1928 secure (secure mode enabled, connected to Steam3)',
            'map' => $this->mapName,
            'players' => sprintf('%d (%d max) (%d queued) (%d joining)', count($this->players), $this->maxPlayers, $this->queuedPlayers, $this->joiningPlayers)
        ];

        // Construct the packet content dynamically with formatting
        $packetContent = sprintf(
            "hostname: %s\n" .
            "version : %s\n" .
            "map     : %s\n" .
            "players : %s\n\n" .
            "id                name    ping connected addr              owner kicks \n",
            $serverStatus['hostname'],
            $serverStatus['version'],
            $serverStatus['map'],
            $serverStatus['players']
        );

        // Add player information
        foreach ($this->players as $player) {
            $packetContent .= sprintf(
                "%-17s \"%s\" %-4s %-9s %-20s %-9s %-9s\n",
                $player->steamId,
                $player->name,
                $player->ping,
                $player->connected,
                $player->ip,
                '0.0', // Placeholder for owner
                '0'    // Placeholder for kicks
            );
        }

        $resultData = json_encode(['Name' => 'WebRcon', 'Identifier' => $this->id, 'Message' => $packetContent]);

        return [$resultData];
    }
}
