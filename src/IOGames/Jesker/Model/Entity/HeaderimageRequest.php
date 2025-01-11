<?php

namespace IOGames\Jesker\Model\Entity;

/**
 * Class HeaderimageRequest.
 */
class HeaderimageRequest extends AbstractRequest
{
    public function __construct(public int $identifier)
    {
    }
}
