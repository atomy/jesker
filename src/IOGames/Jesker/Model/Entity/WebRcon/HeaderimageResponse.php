<?php

namespace IOGames\Jesker\Model\Entity\WebRcon;

use IOGames\Jesker\Model\Entity\AbstractRequest;
use IOGames\Jesker\Model\Entity\Player;

/**
 * Class HeaderimageResponse.
 */
class HeaderimageResponse extends AbstractRequest
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $resultData = json_encode(['Name' => 'WebRcon', 'Identifier' => $this->id, 'Message' => $this->getHeaderimage()]);

        return [$resultData];
    }

    /**
     *
     * @return string
     */
    private function getHeaderimage(): string
    {
        if (empty(getenv('CONVAR_HEADERIMAGE'))) {
            return 'server.headerimage: ""';
        } else {
            return sprintf('server.headerimage: "%s"', getenv('CONVAR_HEADERIMAGE'));
        }
    }
}
