<?php

use \Aegis\Token;
use Aegis\InvalidTokenType;

class TokenTest extends PHPUnit_Framework_TestCase
{
    public function testTokenType()
    {
        $token = new Token(Token::T_VAR, 'value', 5);
        $this->assertEquals($token->getType(), Token::T_VAR, 'Type of token is incorrect');
    }

    public function testTokenValue()
    {
        $token = new Token(Token::T_VAR, 'value');
        $this->assertEquals($token->getValue(), 'value', 'Value of token is incorrect');
    }

    public function testInvalidTokenType()
    {
        $this->expectException(InvalidTokenType::class);
        new Token('invalidtype', 'value', 5);
    }
}
