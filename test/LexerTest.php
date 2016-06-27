<?php

require 'autoload.php';

class LexerTest extends PHPUnit_Framework_TestCase
{
	public function testText()
	{
		$this->tokenTypeTest( 'test', [
			\Aegis\Token::T_TEXT,
		] );

		$this->tokenTypeTest( 'Something { ' . "\n" . ' a little bit more complex }', [
			\Aegis\Token::T_TEXT,
		] );

		$this->tokenTypeTest( 'text with spaces test', [
			\Aegis\Token::T_TEXT,
		] );

		$this->tokenTypeTest( 'testing 1 2 3 @ something random é&é+ - ok', [
			\Aegis\Token::T_TEXT,
		] );
	}

	public function testNumber()
	{
		$this->tokenTypeTest( '{{ 5 }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_NUMBER,
			\Aegis\Token::T_CLOSING_TAG,
		] );

		$this->tokenTypeTest( '{{ 5 }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_NUMBER,
			\Aegis\Token::T_CLOSING_TAG,
		] );
	}

	public function testIf()
	{
		$this->tokenTypeTest( '{{ if @variable }}{{ @variable }}{{ /if }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_CLOSING_TAG,
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_CLOSING_TAG,
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_CLOSING_TAG,
		] );

		$this->tokenTypeTest( '{{ if @variable }}{{ "string" }}text{{ /if }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_CLOSING_TAG,
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_STRING,
			\Aegis\Token::T_CLOSING_TAG,
			\Aegis\Token::T_TEXT,
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_CLOSING_TAG,
		] );
	}

	public function testBlock()
	{
		$this->tokenTypeTest( '{{ block "string" }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_STRING,
			\Aegis\Token::T_CLOSING_TAG,
		] );

		$this->tokenTypeTest( '{{ block "string" }}{{ /block }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_STRING,
			\Aegis\Token::T_CLOSING_TAG,
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_CLOSING_TAG,
		] );

		$this->tokenTypeTest( '{{ block "something" + @variable + @variable.property + "something" }}{{ raw @variable }}{{ /block }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_STRING,
			\Aegis\Token::T_OP,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_OP,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_OP,
			\Aegis\Token::T_STRING,
			\Aegis\Token::T_CLOSING_TAG,
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_CLOSING_TAG,
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_CLOSING_TAG,
		] );

		$this->tokenTypeTest( '{{ block @variable }}this block has content{{ /block }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_CLOSING_TAG,
			\Aegis\Token::T_TEXT,
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_CLOSING_TAG,
		] );

		$this->tokenTypeTest( '{{ block "string"+@test_var }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_STRING,
			\Aegis\Token::T_OP,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_CLOSING_TAG,
		] );

		$this->tokenTypeTest( '{{ block "string" + @test_var }}', [
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_STRING,
			\Aegis\Token::T_OP,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_CLOSING_TAG,
		] );
	}

	public function testRaw()
	{
		$this->tokenTypeTest( 'text with spaces {{ raw "string test " + @variable + "string test" }}', [
			\Aegis\Token::T_TEXT,
			\Aegis\Token::T_OPENING_TAG,
			\Aegis\Token::T_IDENT,
			\Aegis\Token::T_STRING,
			\Aegis\Token::T_OP,
			\Aegis\Token::T_VAR,
			\Aegis\Token::T_OP,
			\Aegis\Token::T_STRING,
			\Aegis\Token::T_CLOSING_TAG,
		] );
	}

	private function tokenTypeTest( $input, $tokens )
	{
		$lexer = new \Aegis\Lexer();
		$stream = $lexer->tokenize( $input );

		$this->assertCount( count( $tokens ), $stream->getTokens(), 'Amount of tokens does not match expected amount' );

		foreach( $tokens as $k => $type )
		{
			$this->assertEquals( $type, $stream->getToken( $k )->getType(), 'Type of token does not match' . $type );
		}
	}
}