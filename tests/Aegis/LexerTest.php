<?php

use \Aegis\Lexer;
use \Aegis\Token;

class LexerTest extends PHPUnit_Framework_TestCase
{
    private $lexer;

    public function setup()
    {
        $this->lexer = new Lexer();
    }

    public function testTokenizeShouldReturnInstanceOfTokenStream()
    {
        $this->tokenizeShouldReturnInstanceOfTokenStream('{{');
        $this->tokenizeShouldReturnInstanceOfTokenStream('}}');
        $this->tokenizeShouldReturnInstanceOfTokenStream('');
        $this->tokenizeShouldReturnInstanceOfTokenStream(' ');
        $this->tokenizeShouldReturnInstanceOfTokenStream('çœøœøø');
        $this->tokenizeShouldReturnInstanceOfTokenStream('{{ "test" }}');
        $this->tokenizeShouldReturnInstanceOfTokenStream('{{}}');
        $this->tokenizeShouldReturnInstanceOfTokenStream('{{ }}');
        $this->tokenizeShouldReturnInstanceOfTokenStream('{--}}');
        $this->tokenizeShouldReturnInstanceOfTokenStream('@test');
        $this->tokenizeShouldReturnInstanceOfTokenStream('{{@test}}');
        $this->tokenizeShouldReturnInstanceOfTokenStream('{{ block }}{{ /block }}');
    }

    public function testTokenValues()
    {
        $this->tokenValueTest('{{', '');
        $this->tokenValueTest(' ', ' ');
        $this->tokenValueTest('test', 'test');
        $this->tokenValueTest('}}', '}}');
        $this->tokenValueTest('@test', '@test');
        $this->tokenValueTest('{}}', '{}}');
        $this->tokenValueTest('{}', '{}');
        $this->tokenValueTest('{}}}', '{}}}');
        $this->tokenValueTest('{{ 5 }}', 5, 1);
        $this->tokenValueTest('{{ 5.8 }}', 5.8, 1);
        $this->tokenValueTest('{{ test}}', 'test', 1);
        $this->tokenValueTest('{{test}}', 'test', 1);
        $this->tokenValueTest('{{test }}', 'test', 1);
        $this->tokenValueTest('{{ test }}', 'test', 1);
    }

    public function testTokenValueTypes()
    {
        $this->tokenValueTypeShouldMatch('my string', 'string');
        $this->tokenValueTypeShouldMatch('{{ "my string" }}', 'string', 1);
        $this->tokenValueTypeShouldMatch('{{ ident }}', 'string', 1);
        $this->tokenValueTypeShouldMatch('{{}}', 'string', 1);
        $this->tokenValueTypeShouldMatch('{{ 100 }}', 'float', 1);
        $this->tokenValueTypeShouldMatch('{{ 540 }}', 'float', 1);
    }

    public function testEmptyStringTypes()
    {
        $this->tokenTypesShouldMatch('', []);
        $this->tokenTypesShouldMatch(' ', [Token::T_TEXT]);
    }

    public function testTokenTypesShouldMatch()
    {
        $this->tokenTypesShouldMatch('', []);
        $this->tokenTypesShouldMatch('çœøœøø', [Token::T_TEXT]);
        $this->tokenTypesShouldMatch('s', [Token::T_TEXT]);
        $this->tokenTypesShouldMatch('1', [Token::T_TEXT]);
        $this->tokenTypesShouldMatch('512', [Token::T_TEXT]);
        $this->tokenTypesShouldMatch('test', [Token::T_TEXT]);
        $this->tokenTypesShouldMatch('Something { '."\n".' a little bit more complex }', [Token::T_TEXT]);
        $this->tokenTypesShouldMatch('text with spaces test', [Token::T_TEXT]);
        $this->tokenTypesShouldMatch('testing 1 2 3 @ something random é&é+ - ok', [Token::T_TEXT]);

        $this->tokenTypesShouldMatch('{{ 5.9 }}', [
            Token::T_OPENING_TAG,
            Token::T_NUMBER,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ my_customfunction(  ) }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_SYMBOL,
            Token::T_SYMBOL,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ someFunction( "some string", 5, anotherfunc() ) }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_SYMBOL,
            Token::T_STRING,
            Token::T_SYMBOL,
            Token::T_NUMBER,
            Token::T_SYMBOL,
            Token::T_IDENT,
            Token::T_SYMBOL,
            Token::T_SYMBOL,
            Token::T_SYMBOL,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ myCustomFunction() }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_SYMBOL,
            Token::T_SYMBOL,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ test() }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_SYMBOL,
            Token::T_SYMBOL,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ test("string") }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_SYMBOL,
            Token::T_STRING,
            Token::T_SYMBOL,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ 5 }}', [
            Token::T_OPENING_TAG,
            Token::T_NUMBER,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ 68}}', [
            Token::T_OPENING_TAG,
            Token::T_NUMBER,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{245 }}', [
            Token::T_OPENING_TAG,
            Token::T_NUMBER,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{100}}', [
            Token::T_OPENING_TAG,
            Token::T_NUMBER,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch("{{ 'this is a string' }}", [
            Token::T_OPENING_TAG,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch("{{ 'this is a string' }}", [
            Token::T_OPENING_TAG,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ "string...,"}}', [
            Token::T_OPENING_TAG,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ if @variable }}{{ @variable }}{{ /if }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_VAR,
            Token::T_CLOSING_TAG,
            Token::T_OPENING_TAG,
            Token::T_VAR,
            Token::T_CLOSING_TAG,
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ if @variable }}{{ "string" }}text{{ /if }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_VAR,
            Token::T_CLOSING_TAG,
            Token::T_OPENING_TAG,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
            Token::T_TEXT,
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{if @variable}}{{ "string"}}text{{/if }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_VAR,
            Token::T_CLOSING_TAG,
            Token::T_OPENING_TAG,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
            Token::T_TEXT,
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ block "string" }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ block "string" }}{{ /block }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ block "something" + @variable + @variable.property + "something" }}{{ raw @variable }}{{ /block }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_STRING,
            Token::T_OP,
            Token::T_VAR,
            Token::T_OP,
            Token::T_VAR,
            Token::T_OP,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_VAR,
            Token::T_CLOSING_TAG,
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ block @variable }}this block has content{{ /block }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_VAR,
            Token::T_CLOSING_TAG,
            Token::T_TEXT,
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ block "string"+@test_var }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_STRING,
            Token::T_OP,
            Token::T_VAR,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{block "string" + @test_var}}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_STRING,
            Token::T_OP,
            Token::T_VAR,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('text with spaces {{ raw "string test " + @variable + "string test" }}', [
            Token::T_TEXT,
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_STRING,
            Token::T_OP,
            Token::T_VAR,
            Token::T_OP,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
        ]);

        $this->tokenTypesShouldMatch('{{ block "blockname" }}some raw text{{ /block }}', [
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_STRING,
            Token::T_CLOSING_TAG,
            Token::T_TEXT,
            Token::T_OPENING_TAG,
            Token::T_IDENT,
            Token::T_CLOSING_TAG,
        ]);
    }

    private function tokenTypesShouldMatch($input, $tokens)
    {
        $stream = $this->lexer->tokenize($input);

        $this->assertCount(count($tokens), $stream->getTokens(), 'Amount of tokens does not match expected amount');

        foreach ($tokens as $k => $type) {
            $this->assertEquals($type, $stream->getToken($k)->getType(), 'Type of token does not match '.$type);
        }
    }

    private function tokenValueTest($input, $value, $position = 0)
    {
        $stream = $this->lexer->tokenize($input);
        $this->assertEquals($value, $stream->getToken($position)->getValue(), 'Value of token does not match '.$value);
    }

    private function tokenizeShouldReturnInstanceOfTokenStream($input)
    {
        $this->assertInstanceOf(\Aegis\TokenStream::class, $this->lexer->tokenize($input));
    }

    private function tokenValueTypeShouldMatch($input, $type, $position = 0)
    {
        $stream = $this->lexer->tokenize($input);
        $this->assertInternalType($type, $stream->getToken($position)->getValue(), 'Type of value of token does not match '.$type);
    }
}
