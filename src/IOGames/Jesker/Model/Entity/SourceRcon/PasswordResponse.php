<?php

namespace IOGames\Jesker\Model\Entity\SourceRcon;

class PasswordResponse extends AbstractSourceRcon
{
    /**
     * @return array
     */
    public function getData()
    {
        return ['0a000000b0040000000000000000', '0a000000b0040000020000000000'];
    }
}