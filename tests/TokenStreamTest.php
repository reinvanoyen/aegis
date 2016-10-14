<?php

use \Aegis\Token;
use \Aegis\TokenStream;

class TokenStreamTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_VAR, 'value1', 10));
        $this->assertCount(1, $stream->getTokens(), 'Amount of tokens is not correct');
        $stream->addToken(new Token(Token::T_VAR, 'value2', 10));
        $this->assertCount(2, $stream->getTokens(), 'Amount of tokens is not correct');
        $this->assertEquals($stream->getToken(0)->getValue(), 'value1', 'Value of token is not correct');
        $this->assertEquals($stream->getToken(1)->getValue(), 'value2', 'Value of token is not correct');
    }
}