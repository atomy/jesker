<?php

namespace IOGames\Jesker\Model\Entity\WebRcon;

use IOGames\Jesker\Model\Entity\AbstractResponse;

class PasswordResponse extends AbstractResponse
{
    public function __construct(public bool $wrongPassswordError, public array $headers)
    {
    }

    /**
     * Calculate Sec-WebSocket-Accept key and return WebSocket handshake response headers.
     *
     * @return array
     */
    public function getData(): array
    {
        if (true === $this->wrongPassswordError) {
            return [
                "HTTP/1.1 401 Unauthorized\r\n" .
                "Content-Type: text/plain\r\n" .
                "Connection: close\r\n" .
                "\r\n" . // Separates headers from the body
                "Invalid password."
            ];
        }

        // Ensure we have the Sec-WebSocket-Key header
        if (!isset($this->headers['Sec-WebSocket-Key'])) {
            return ['HTTP/1.1 400 Bad Request'];
        }

        // Step 1: Get the Sec-WebSocket-Key from headers
        $secWebSocketKey = $this->headers['Sec-WebSocket-Key'];

        // Step 2: Append the WebSocket "magic string"
        $magicString = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";
        $keyWithMagic = $secWebSocketKey . $magicString;

        // Step 3: Hash with SHA-1 and Base64 encode the result
        $hashed = sha1($keyWithMagic, true);
        $secWebSocketAccept = base64_encode($hashed);

        // Step 4: Build the WebSocket handshake response headers
        return ["HTTP/1.1 101 Switching Protocols\r\n" .
            "Upgrade: websocket\r\n" .
            "Connection: Upgrade\r\n" .
            "Sec-WebSocket-Accept: " . $secWebSocketAccept . "\r\n\r\n"];
    }
}
