<?php

namespace Aegis\Parser;

use Aegis\Contracts\NodeCollectionInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Exception\AegisError;
use Aegis\Exception\InvalidTokenType;
use Aegis\Exception\ParseError;
use Aegis\Exception\SyntaxError;
use Aegis\Node\TextNode;
use Aegis\Node\Node;
use Aegis\Token\Token;
use Aegis\Token\TokenType;
use Aegis\Lexer\TokenStream;

/**
 * This class is responsible for parsing a TokenStream into an Abstract Syntax Tree (AST)
 *
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class Parser implements ParserInterface
{
    /**
     * @var NodeCollectionInterface
     */
    private $nodeCollection;

    /**
     * @var AbstractSyntaxTree
     */
    private $ast;

    /**
     * @var Node
     */
    private $scope;

    /**
     * @var int
     */
    private $cursor;

    /**
     * @var int
     */
    private $lastTokenIndex;

    /**
     * @var TokenStream
     */
    private $stream;

    /**
     * Parser constructor.
     * @param NodeCollectionInterface $nodeCollection
     */
    public function __construct(NodeCollectionInterface $nodeCollection)
    {
        $this->nodeCollection = $nodeCollection;
    }

    /**
     * Parses the given TokenStream into an AbstractSyntaxTree
     *
     * @param TokenStream $stream
     * @return AbstractSyntaxTree
     * @throws AegisError
     */
    public function parse(TokenStream $stream): AbstractSyntaxTree
    {
        $this->prepare($stream);
        $this->parseOutsideTag();

        if ($this->cursor !== $this->lastTokenIndex) {
            $this->syntaxError('Unexpected token ' . $this->getCurrentToken());
        }

        return $this->getAst();
    }

    /**
     * Prepares the parser for parsing
     *
     * @param TokenStream $stream
     * @return void
     */
    public function prepare(TokenStream $stream): void
    {
        $this->ast = new AbstractSyntaxTree();
        $this->scope = $this->ast;
        $this->stream = $stream;
        $this->lastTokenIndex = max(0, count($this->stream->getTokens()) - 1);
        $this->cursor = 0;
    }

    /**
     * Parses outside of tags
     *
     * @return void
     */
    public function parseOutsideTag(): void
    {
        if (!count($this->stream->getTokens())) {
            return;
        }

        if ($this->accept(TokenType::T_TEXT)) {
            $this->insert(new TextNode($this->getCurrentToken()->getValue()));
            $this->advance();
        }

        if ($this->skip(TokenType::T_OPENING_TAG)) {
            $this->parseStatement();
        }
    }

    /**
     * Parses text
     *
     * @return void
     */
    public function parseText(): void
    {
        if (!count($this->stream->getTokens())) {
            return;
        }

        if ($this->accept(TokenType::T_TEXT)) {
            $this->insert(new TextNode($this->getCurrentToken()->getValue()));
            $this->advance();
        }
    }

    /**
     * Parses a statement
     *
     * @return void
     */
    private function parseStatement(): void
    {
        $this->skip(TokenType::T_WHITESPACE);

        foreach ($this->nodeCollection->getAll() as $node) {
            $node::parse($this);
        }
    }

    /**
     * Expects the current token to be of given type and optionally has given value
     *
     * @param int $type
     * @param null $value
     * @return bool
     * @throws InvalidTokenType
     * @throws SyntaxError
     */
    public function expect(int $type, $value = null): bool
    {
        if (!$this->accept($type, $value)) {
            $this->syntaxError('Expected '.(new Token($type, $value))->getStringRepresentation().' got '.$this->getCurrentToken());
        }

        return true;
    }

    /**
     * Expects the next token to be of given type and optionally has given value
     *
     * @param int $type
     * @param null $value
     * @return bool
     * @throws InvalidTokenType
     * @throws SyntaxError
     */
    public function expectNext(int $type, $value = null): bool
    {
        if (!$this->acceptNext($type, $value)) {
            $this->syntaxError('Expected '.(new Token($type, $value))->getStringRepresentation().' got '.$this->getNextToken());
        }

        return true;
    }

    /**
     * Skips the current token if it's of given type and optionally has given value
     *
     * @param int $type
     * @param null $value
     * @return bool
     */
    public function skip(int $type, $value = null): bool
    {
        if ($this->accept($type, $value)) {
            $this->advance();

            return true;
        }

        return false;
    }

    /**
     * Accepts the current token if it's of given type and optionally has given value
     *
     * @param int $type
     * @param null $value
     * @return bool
     */
    public function accept(int $type, $value = null): bool
    {
        $this->skipWhitespace();

        if ($this->getCurrentToken()->getType() === $type) {
            if ($value) {
                return ($this->getCurrentToken()->getValue() === $value);
            }

            return true;
        }

        return false;
    }

    private function skipWhitespace()
    {
        while ($this->getCurrentToken()->getType() === TokenType::T_WHITESPACE) {
            $this->advance();
        }
    }

    /**
     * Accepts the next token if it's of given type and optionally has given value
     *
     * @param int $type
     * @param null $value
     * @return bool
     */
    public function acceptNext(int $type, $value = null): bool
    {
        if ($this->getNextToken()->getType() === $type) {
            if ($value) {
                return ($this->getNextToken()->getValue() === $value);
            }

            return true;
        }

        return false;
    }

    /**
     * Gets the token at the cursor position
     *
     * @return Token
     */
    public function getCurrentToken(): Token
    {
        return $this->stream->getToken($this->cursor);
    }

    /**
     * Gets the token at the next position from the cursor
     *
     * @return Token
     */
    public function getNextToken(): Token
    {
        return $this->stream->getToken($this->cursor + 1);
    }

    /**
     * Sets the parsing scope
     *
     * @param Node $scope
     * @return void
     */
    public function setScope(Node $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * Gets the parsing scope
     *
     * @return Node
     * @throws AegisError
     */
    public function getScope(): Node
    {
        if (!$this->scope) {
            throw new AegisError('No scope available before parsing');
        }

        return $this->scope;
    }

    /**
     * Gets the AST
     *
     * @return AbstractSyntaxTree
     * @throws AegisError
     */
    public function getAst(): AbstractSyntaxTree
    {
        if (!$this->ast) {
            throw new AegisError('No AbstractSyntaxTree was set');
        }

        return $this->ast;
    }

    /**
     * Moves inside the last inserted node
     *
     * @return void
     */
    public function traverseUp(): void
    {
        $this->setScope($this->getScope()->getLastChild());
    }

    /**
     * Moves outside the current scope
     *
     * @return void
     * @throws ParseError
     */
    public function traverseDown(): void
    {
        try {
            $parent = $this->getScope()->getParent();
        } catch (AegisError $e) {
            throw new ParseError($e->getMessage());
        }

        $this->setScope($parent);
    }

    /**
     * Advances the cursor
     *
     * @return void
     */
    public function advance(): void
    {
        if ($this->cursor < count($this->stream->getTokens()) - 1) {
            ++$this->cursor;
        }
    }

    /**
     * Moves to the root node (the root of the AST)
     *
     * @return void
     */
    public function root(): void
    {
        $this->setScope($this->ast);
    }

    /**
     * Wraps the last inserted node with the given node
     *
     * @param Node $node
     * @return void
     */
    public function wrap(Node $node): void
    {
        $last = $this->scope->getLastChild(); // Get the last inserted node
        $this->scope->removeLastChild(); // Remove it

        $this->insert($node);
        $this->traverseUp();

        $this->insert($last);
    }

    /**
     * Sets the last inserted node as attribute
     *
     * @param string $attrName
     * @return void
     */
    public function setAttribute(string $attrName): void
    {
        $last = $this->scope->getLastChild(); // Get the last inserted node
        $this->scope->removeLastChild(); // Remove it
        $this->scope->setAttribute($attrName, $last);
    }

    /**
     * Inserts the given node
     *
     * @param Node $node
     * @return void
     */
    public function insert(Node $node): void
    {
        $node->setParent($this->scope);
        $this->scope->insert($node);
    }

    /**
     * @param string $message
     * @return void
     * @throws SyntaxError
     */
    public function syntaxError(string $message = ''): void
    {
        throw new SyntaxError($message, $this->stream, $this->getCurrentToken());
    }
}
