<?php

namespace IOGames\Jesker\Model\Entity\SourceRcon;

use IOGames\Jesker\Model\Entity\AbstractResponse;

class PasswordResponse extends AbstractResponse
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return ['0a000000b0040000000000000000', '0a000000b0040000020000000000'];
    }
}