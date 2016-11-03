<?php

namespace Aegis;

class InvalidTokenType extends AegisError
{
    public function __construct($tokenType)
    {
        parent::__construct('Invalid token type '.$tokenType);

        $this->tokenType = $tokenType;
    }

    public function getTokenType()
    {
        return $this->tokenType;
    }
}
