<?php

use Aegis\Node\RootNode;
use Aegis\TokenStream;
use Aegis\Parser;
use Aegis\Token;

class ParserTest extends PHPUnit_Framework_TestCase
{
    private $parser;

    public function setup()
    {
        $this->parser = new Parser();
        $this->parser->setRuntime(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
    }

    public function testParseShouldReturnInstanceOfRootNode()
    {
        $stream = new TokenStream();
        $this->assertInstanceOf(RootNode::class, $this->parser->parse($stream));
    }

    public function testGetCurrentTokenType()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'block', 1));
        $stream->addToken(new Token(Token::T_OP, '+', 1));
        $stream->addToken(new Token(Token::T_OPENING_TAG, '{{', 1));
        $stream->addToken(new Token(Token::T_IDENT, 'ident', 1));
        $stream->addToken(new Token(Token::T_NUMBER, '10', 1));

        $this->parser->prepare($stream);
        $this->parser->skip(Token::T_IDENT);
        $this->parser->skip(Token::T_OP);
        $this->parser->skip(Token::T_OPENING_TAG);
        $this->parser->skip(Token::T_IDENT);

        $this->assertEquals(Token::T_NUMBER, $this->parser->getCurrentToken()->getType());
    }

    public function testGetScopeShouldThrowError()
    {
        $this->expectException(Aegis\AegisError::class);
        $this->parser->getScope();
    }

    public function testTraverseDownShouldThrowParseError()
    {
        $this->expectException(Aegis\ParseError::class);

        $stream = new TokenStream();
        $this->parser->parse($stream);
        $this->parser->traverseDown();
    }

    public function testGetCurrentTokenShouldThrowNoTokenAtIndex()
    {
        $this->expectException(\Aegis\Exception\NoTokenAtIndex::class);

        $stream = new TokenStream();
        $this->parser->parse($stream);
        $this->parser->getCurrentToken();
    }

    public function testExpectShouldThrowSyntaxError()
    {
        $this->expectException(\Aegis\Exception\SyntaxError::class);

        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'ident'));

        $this->parser->parse($stream);
        $this->parser->expect(Token::T_OP);
    }

    public function testExpectWithValueShouldThrowSyntaxError()
    {
        $this->setExpectedException(\Aegis\Exception\SyntaxError::class);

        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'correctvalue'));

        $this->parser->parse($stream);
        $this->parser->expect(Token::T_IDENT, 'wrongvalue');
    }

    public function testExpectWithValueShouldShouldReturnTrue()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'correctvalue', 1));

        $this->parser->parse($stream);
        $this->assertEquals(true, $this->parser->expect(Token::T_IDENT, 'correctvalue'));
    }

    public function testAcceptWithValueShouldShouldReturnTrue()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'correctvalue', 1));

        $this->parser->parse($stream);
        $this->assertEquals(true, $this->parser->accept(Token::T_IDENT, 'correctvalue'));
    }

    public function testAcceptWithValueShouldShouldReturnFalse()
    {
        $stream = new TokenStream();
        $stream->addToken(new Token(Token::T_IDENT, 'correctvalue', 1));

        $this->parser->parse($stream);
        $this->assertEquals(false, $this->parser->accept(Token::T_IDENT, 'wrongvalue'));
    }
}
