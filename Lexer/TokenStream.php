<?php

require_once 'Token/Token.php';

class TokenStream implements \JsonSerializable
{
	private $tokens = [];

	public function addToken( Token $token )
	{
		$this->tokens[] = $token;
	}

	public function getToken( $i )
	{
		return $this->tokens[ $i ];
	}

	public function getTokens()
	{
		return $this->tokens;
	}

	public function __toString()
	{
		$string = '';

		foreach( $this->tokens as $t )
		{
			$string .= $t . "<br />";
		}

		return $string;
	}

	public function jsonSerialize()
	{
		return $this->tokens;
	}
}