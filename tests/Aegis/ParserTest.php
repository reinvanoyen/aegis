<?php

use Aegis\Node\RootNode;
use Aegis\TokenStream;
use Aegis\Parser;
use Aegis\Token;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testParseShouldReturnInstanceOfRootNode()
    {
        $stream = new TokenStream();
        $parser = new Parser();

        $this->assertInstanceOf(RootNode::class, $parser->parse($stream));
    }

    public function testGetCurrentTokenType()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'ident', 1));
        $stream->addToken(new Token(Token::T_OP, '+', 1));
        $stream->addToken(new Token(Token::T_OPENING_TAG, '{{', 1));
        $stream->addToken(new Token(Token::T_IDENT, 'ident', 1));
        $stream->addToken(new Token(Token::T_NUMBER, '10', 1));

        $parser = new Parser();
        $parser->parse($stream);
        $parser->skip(Token::T_IDENT);
        $parser->skip(Token::T_OP);
        $parser->skip(Token::T_OPENING_TAG);
        $parser->skip(Token::T_IDENT);

        $this->assertEquals(Token::T_NUMBER, $parser->getCurrentToken()->getType());
    }

    public function testGetScopeShouldThrowError()
    {
        $this->expectException(Aegis\AegisError::class);

        $parser = new Parser();
        $parser->getScope();
    }

    public function testTraverseDownShouldThrowParseError()
    {
        $this->expectException(Aegis\ParseError::class);

        $stream = new TokenStream();
        $parser = new Parser();
        $parser->parse($stream);
        $parser->traverseDown();
    }

    public function testGetCurrentTokenShouldThrowNoTokenAtIndex()
    {
        $this->expectException(Aegis\NoTokenAtIndex::class);

        $stream = new TokenStream();
        $parser = new Parser();
        $parser->parse($stream);
        $parser->getCurrentToken();
    }

    public function testExpectShouldThrowParseError()
    {
        $this->expectException(Aegis\ParseError::class);

        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'ident', 1));

        $parser = new Parser();
        $parser->parse($stream);
        $parser->expect(Token::T_OP);
    }

    public function testExpectWithValueShouldThrowParseError()
    {
        $this->setExpectedException(Aegis\ParseError::class);

        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'correctvalue', 1));

        $parser = new Parser();
        $parser->parse($stream);
        $parser->expect(Token::T_IDENT, 'wrongvalue');
    }

    public function testExpectWithValueShouldShouldReturnTrue()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'correctvalue', 1));

        $parser = new Parser();
        $parser->parse($stream);
        $this->assertEquals(true, $parser->expect(Token::T_IDENT, 'correctvalue'));
    }

    public function testAcceptWithValueShouldShouldReturnTrue()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'correctvalue', 1));

        $parser = new Parser();
        $parser->parse($stream);
        $this->assertEquals(true, $parser->accept(Token::T_IDENT, 'correctvalue'));
    }

    public function testAcceptWithValueShouldShouldReturnFalse()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'correctvalue', 1));

        $parser = new Parser();
        $parser->parse($stream);
        $this->assertEquals(false, $parser->accept(Token::T_IDENT, 'wrongvalue'));
    }
}
