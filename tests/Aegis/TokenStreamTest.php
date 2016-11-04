<?php

use \Aegis\Token;
use \Aegis\TokenStream;
use \Aegis\NoTokenAtIndex;

class TokenStreamTest extends PHPUnit_Framework_TestCase
{
    public function testAddToken()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_VAR, 'value', 10));
        $this->assertCount(1, $stream->getTokens(), 'Amount of tokens is not correct');
    }

    public function testAddedTokenTypeString()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_VAR, 'value', 10));
        $this->assertInternalType('string', $stream->getToken(0)->getValue());
    }

    public function testAddedTokenTypeFloat()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_NUMBER, '5', 10));
        $this->assertInternalType('float', $stream->getToken(0)->getValue());
    }

    public function testGetTokenShouldReturnInstanceOfToken()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_VAR, 'value', 10));
        $this->assertInstanceOf(Token::class, $stream->getToken(0));
    }

    public function testGetTokenShouldThrowException()
    {
        $this->expectException(NoTokenAtIndex::class);

        $stream = new TokenStream();
        $stream->getToken(3);
    }
}
