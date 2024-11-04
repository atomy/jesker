<?php

namespace IOGames\Jesker\Model\Entity\SourceRcon;

use IOGames\Jesker\Model\Entity\AbstractResponse;
use IOGames\Jesker\Model\Entity\Player;
use IOGames\Jesker\Service\PacketHelper;

class StatusResponse extends AbstractResponse
{
    const PAKET_CODE_RESPONSE = '03';

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

    /**
     * @return array
     */
    public function getData(): array
    {
        $content = sprintf('%s%s000000000000%s', $this->packetId, self::PAKET_CODE_RESPONSE, $this->getServerStatus());
        $packetSize = PacketHelper::calculatePacketSize($content);

        return [
            '2a00000000000000040000005b52434f4e5d5b39352e39302e3231342e323a32363032365d207374617475730000',
            sprintf('%s%s', $packetSize, $content) // Packet with players
        ];
    }

    /**
     * Build and return *status* response.
     *
     * @return string
     */
    private function getServerStatus(): string
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

        // Convert the content to hex
        return bin2hex($packetContent);
    }
}
