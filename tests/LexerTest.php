<?php

require_once 'Lexer/Lexer.php';

class LexerTest extends PHPUnit_Framework_TestCase
{
	public function testTokenTypes()
	{
		$lexer = new Lexer();

		$stream = $lexer->tokenize( 'text test' );

		$this->assertEquals( Token::T_TEXT, $stream->getToken( 0 )->getType() );
		$this->assertCount( 1, $stream->getTokens() );

		$stream = $lexer->tokenize( 'text test {{ @var }}' );

		$this->assertCount( 4, $stream->getTokens() );
		$this->assertEquals( Token::T_TEXT, $stream->getToken( 0 )->getType() );
		$this->assertEquals( Token::T_OPENING_TAG, $stream->getToken( 1 )->getType() );
		$this->assertEquals( Token::T_VAR, $stream->getToken( 2 )->getType() );
		$this->assertEquals( Token::T_CLOSING_TAG, $stream->getToken( 3 )->getType() );

		$stream = $lexer->tokenize( '{{@var}}' );

		$this->assertCount( 3, $stream->getTokens() );
		$this->assertEquals( Token::T_OPENING_TAG, $stream->getToken( 0 )->getType() );
		$this->assertEquals( Token::T_VAR, $stream->getToken( 1 )->getType() );
		$this->assertEquals( Token::T_CLOSING_TAG, $stream->getToken( 2 )->getType() );

		$stream = $lexer->tokenize( '{{ block "test" }}' );

		$this->assertCount( 4, $stream->getTokens() );
		$this->assertEquals( Token::T_OPENING_TAG, $stream->getToken( 0 )->getType() );
		$this->assertEquals( Token::T_IDENT, $stream->getToken( 1 )->getType() );
		$this->assertEquals( Token::T_STRING, $stream->getToken( 2 )->getType() );
		$this->assertEquals( Token::T_CLOSING_TAG, $stream->getToken( 3 )->getType() );
	}
}