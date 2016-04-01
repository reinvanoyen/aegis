<?php

require_once 'Token/Token.php';

class TokenStream
{
	private $tokens = [];

	public function addToken( Token $token )
	{
		$this->tokens[] = $token;
	}

	public function __toString()
	{
		$string = '';

		foreach( $this->tokens as $t )
		{
			$string .= $t . "\n";
		}

		return $string;
	}
}