<?php

use \Aegis\Token;

class TokenTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $token = new Token(Token::T_VAR, 'value', 5);

        $this->assertEquals($token->getType(), Token::T_VAR, 'Type of token is incorrect');
        $this->assertEquals($token->getValue(), 'value', 'Value of token is incorrect');
        $this->assertEquals($token->getLine(), 5, 'Line of token is incorrect');
    }

    public function testInvalidTokenType()
    {
        $this->setExpectedException(\Aegis\InvalidTokenType::class);
        $token = new Token('invalidtype', 'value', 5);
    }
}
