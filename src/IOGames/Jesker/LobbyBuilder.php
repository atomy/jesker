<?php

namespace IOGames\Jesker;

use IOGames\Jesker\Model\Entity\Player;

class LobbyBuilder
{
    private static ?LobbyBuilder $instance = null;
    private array $players = [];
    private array $usedNames = [];
    private array $usedSteamIds = [];

    // Expanded list of unique names
    private static array $names = [
        'GamerGuy', 'ProPlayer', 'NoobMaster', 'EpicGamer', 'SniperKing',
        'StealthNinja', 'Firestorm', 'NightHawk', 'IronFist', 'WizardOfOz',
        'ShadowHunter', 'DragonSlayer', 'TitanCrusher', 'ZombiKiller', 'SpaceCowboy',
        'PixelPioneer', 'RogueAgent', 'CyberSamurai', 'BattleBard', 'JediKnight',
        'TechWizard', 'FrostByte', 'MysticMage', 'WarriorPrincess', 'GoldenEagle',
        'PhantomThief', 'SilverFox', 'CrimsonWraith', 'StormBreaker', 'LightningBolt',
        'NovaHunter', 'ShadowAssassin', 'FrostFire', 'CyberGuardian', 'MysticWarlock',
        'RagingBull', 'SteelTitan', 'GhostRider', 'WildCard', 'PhantomNinja',
        'StormChaser', 'ThunderStrike', 'SilentWolf', 'VenomousViper', 'MightyDragon',
        'AlphaWolf', 'InfernoKnight', 'StealthPanther', 'VortexSorcerer', 'IronGiant',
        'BlazingPhoenix', 'NobleKnight', 'SilverDragon', 'BattleMage', 'VoidWalker',
        'EchoFox', 'SwiftBlade', 'MysticNinja', 'CrimsonKnight', 'BlizzardWizard',
        'GhostHunter', 'CelestialGuardian', 'DemonSlayer', 'AstralRider', 'GoldenPhoenix',
        'FallenAngel', 'TitanSlayer', 'EmeraldSorcerer', 'VenomStrike', 'SolarEagle',
        'CrystalPaladin', 'FrostGiant', 'SkyWarrior', 'InfernalMage', 'NinjaMaster',
        // Add more names here...
    ];

    private const MAX_PLAYERS = 6000;

    /**
     * @return LobbyBuilder
     */
    public static function getInstance(): LobbyBuilder
    {
        if (self::$instance === null) {
            self::$instance = new LobbyBuilder();
        }
        return self::$instance;
    }

    /**
     * Return players if none exists yet, create them.
     *
     * @param int $count
     * @return array
     */
    public function getPlayers(int $count = 1): array
    {
        if (empty($this->players)) {
            $this->createLobby($count);
        }

        return $this->players;
    }

    /**
     * @param int $count
     * @return void
     */
    private function createLobby(int $count): void
    {
        if ($count > self::MAX_PLAYERS) {
            throw new \InvalidArgumentException("Cannot create more than " . self::MAX_PLAYERS . " players.");
        }

        $this->usedNames = [];
        $this->usedSteamIds = [];
        $namesCount = count(self::$names);

        // Shuffle names array to randomize name selection
        $shuffledNames = self::$names;
        shuffle($shuffledNames);

        for ($i = 0; $i < $count; $i++) {
            // Check for unique name availability
            if ($i < $namesCount) {
                $name = $shuffledNames[$i]; // Use unique shuffled names
            } else {
                $name = $this->generateUniqueName($i); // Generate a unique name if we run out
            }

            $steamId = $this->generateUniqueSteamId();
            $ping = rand(1, 100); // Random ping between 1 and 100 ms
            $connected = $this->generateRandomConnectedTime(); // Generate random connected time
            $ip = $this->generateRandomIp(); // Generate random IP

            $this->players[] = new Player($steamId, $name, $ping, $connected, $ip);
        }
    }

    private function generateUniqueSteamId(): int
    {
        do {
            $steamId = rand(76561197960265728, 76561197990000000); // Example Steam ID range
        } while (in_array($steamId, $this->usedSteamIds));

        $this->usedSteamIds[] = $steamId; // Mark this Steam ID as used
        return $steamId;
    }

    private function generateUniqueName(int $index): string
    {
        // Generate a unique name based on index
        return 'Player_' . $index . '_' . uniqid();
    }

    private function generateRandomConnectedTime(): string
    {
        // Simulate a random connected time in a readable format
        return sprintf('%d.%03ds', rand(1, 3600), rand(0, 999)); // Up to 1 hour and milliseconds
    }

    private function generateRandomIp(): string
    {
        // Generate a random IPv4 address
        return sprintf('%d.%d.%d.%d', rand(1, 255), rand(0, 255), rand(0, 255), rand(0, 255));
    }
}
