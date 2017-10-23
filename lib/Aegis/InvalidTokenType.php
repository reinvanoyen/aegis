<?php

namespace Aegis;

/**
 * Class InvalidTokenType
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class InvalidTokenType extends AegisError
{
    /**
     * @var string
     */
    private $tokenType;

    /**
     * InvalidTokenType constructor.
     * @param string $tokenType
     */
    public function __construct(string $tokenType)
    {
        parent::__construct('Invalid token type '.$tokenType);

        $this->tokenType = $tokenType;
    }

    /**
     * @return string
     */
    public function getTokenType() : string
    {
        return $this->tokenType;
    }
}