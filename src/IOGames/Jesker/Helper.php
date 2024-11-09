<?php

namespace IOGames\Jesker;

/**
 * Class Helper.
 *
 * Providing helping functions.
 */
class Helper
{
    /**
     * Access env and validate it's content.
     *
     * @param string $envName env key
     * @return array|string
     */
    public static function validateAndGetEnv(string $envName): array|string
    {
        $envValue = getenv($envName);

        // Check if the environment variable is empty
        if ($envValue === false || $envValue === '') {
            throw new \InvalidArgumentException("Environment variable '{$envName}' is not set or is empty.");
        }

        return $envValue;
    }
}