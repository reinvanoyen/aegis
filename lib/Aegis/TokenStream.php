<?php

namespace Aegis;

class TokenStream
{
    private $tokens = [];

    public function addToken(Token $token)
    {
        $this->tokens[] = $token;
    }

    public function getToken($i)
    {
        if (!isset($this->tokens[$i])) {
            throw new NoTokenAtIndex($i);
        }

        return $this->tokens[$i];
    }

    public function getTokens()
    {
        return $this->tokens;
    }
}
