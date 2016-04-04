<?php

require_once 'Lexer/Lexer.php';

class TokenTest extends PHPUnit_Framework_TestCase
{
	public function test()
	{
		$this->tokenTypeTest( 'test', [
			Token::T_TEXT
		] );

		$this->tokenTypeTest( 'text with spaces test', [
			Token::T_TEXT
		] );

		$this->tokenTypeTest( '{{ block "string" }}', [
			Token::T_OPENING_TAG,
			Token::T_IDENT,
			Token::T_STRING,
			Token::T_CLOSING_TAG,
		] );

		$this->tokenTypeTest( '{{ block "string"+@test_var }}', [
			Token::T_OPENING_TAG,
			Token::T_IDENT,
			Token::T_STRING,
			Token::T_OP,
			Token::T_VAR,
			Token::T_CLOSING_TAG,
		] );

		$this->tokenTypeTest( 'text with spaces {{ raw "string test " + @variable + "string test" }}', [
			Token::T_TEXT,
			Token::T_OPENING_TAG,
			Token::T_IDENT,
			Token::T_STRING,
			Token::T_OP,
			Token::T_VAR,
			Token::T_OP,
			Token::T_STRING,
			Token::T_CLOSING_TAG,
		] );
	}

	private function tokenTypeTest( $input, $tokens )
	{
		$lexer = new Lexer();
		$stream = $lexer->tokenize( $input );

		$this->assertCount( count( $tokens ), $stream->getTokens(), 'Amount of tokens does not match expected amount' );

		foreach( $tokens as $k => $type )
		{
			$this->assertEquals( $type, $stream->getToken( $k )->getType(), 'Type of token does not match' . $type );
		}
	}
}