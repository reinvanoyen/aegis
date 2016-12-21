<?php

use \Aegis\Token;
use \Aegis\TokenStream;
use \Aegis\NoTokenAtIndex;

class TokenStreamTest extends PHPUnit_Framework_TestCase
{
    private $stream;

    public function setup()
    {
        $this->stream = new TokenStream();
    }

    public function testAddToken()
    {
        $this->stream->addToken(new Token(Token::T_VAR, 'value', 10));
        $this->assertCount(1, $this->stream->getTokens(), 'Amount of tokens is not correct');
    }

    public function testAddedTokenTypeString()
    {
        $this->stream->addToken(new Token(Token::T_VAR, 'value', 10));
        $this->assertInternalType('string', $this->stream->getToken(0)->getValue());
    }

    public function testAddedTokenTypeFloat()
    {
        $this->stream->addToken(new Token(Token::T_NUMBER, '5', 10));
        $this->assertInternalType('float', $this->stream->getToken(0)->getValue());
    }

    public function testGetTokenShouldReturnInstanceOfToken()
    {
        $this->stream->addToken(new Token(Token::T_VAR, 'value', 10));
        $this->assertInstanceOf(Token::class, $this->stream->getToken(0));
    }

    public function testGetTokenShouldThrowException()
    {
        $this->expectException(NoTokenAtIndex::class);
        $this->stream->getToken(3);
    }
}
