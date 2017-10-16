<?php

namespace Aegis;

/**
 * Class TokenStream
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */

class TokenStream
{
    private $tokens = [];

	/**
	 * @param Token $token
	 */
    public function addToken(Token $token)
    {
        $this->tokens[] = $token;
    }

	/**
	 * @param $i
	 * @return mixed
	 * @throws NoTokenAtIndex
	 */
    public function getToken($i)
    {
        if (!isset($this->tokens[$i])) {
            throw new NoTokenAtIndex($i);
        }

        return $this->tokens[$i];
    }

	/**
	 * @return array
	 */
    public function getTokens()
    {
        return $this->tokens;
    }
}
