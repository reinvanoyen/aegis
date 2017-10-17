<?php

namespace Aegis;

/**
 * Class TokenStream
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class TokenStream
{
    /**
     * @var Token[]
     */
    private $tokens = [];

    /**
     * Adds a token to the TokenStream
     *
     * @param Token $token
     */
    public function addToken(Token $token) : void
    {
        $this->tokens[] = $token;
    }

    /**
     * Gets a token at given index
     *
     * @param $index
     * @return Token
     * @throws NoTokenAtIndex
     */
    public function getToken($index) : Token
    {
        if (!isset($this->tokens[$index])) {
            throw new Error\NoTokenAtIndex($index);
        }

        return $this->tokens[$index];
    }

    /**
     * Gets all tokens
     *
     * @return Token[]
     */
    public function getTokens() : array
    {
        return $this->tokens;
    }
}
