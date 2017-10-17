<?php

namespace Aegis;

use Aegis\Error\SyntaxError;
use Aegis\Node\RootNode;
use Aegis\Node\TextNode;

/**
 * This class is responsible for parsing a TokenStream into a RootNode (aka Abstract Syntax Tree)
 *
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class Parser implements ParserInterface
{
    /**
     * @var RuntimeInterface
     */
    private $runtime;

    /**
     * @var RootNode
     */
    private $root;

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
     * Sets the runtime
     *
     * @param RuntimeInterface $runtime
     * @return void
     */
    public function setRuntime(RuntimeInterface $runtime) : void
    {
        $this->runtime = $runtime;
    }

    /**
     * Parses the given TokenStream into a RootNode (AST)
     *
     * @param TokenStream $stream
     * @return RootNode
     * @throws AegisError
     */
    public function parse(TokenStream $stream) : RootNode
    {
        if (! $this->runtime) {
            throw new AegisError('Runtime needs to be set before parsing');
        }

        $this->prepare($stream);
        $this->parseOutsideTag();

        if ($this->cursor !== $this->lastTokenIndex) {
            $this->syntaxError('Unexpected token ' . $this->getCurrentToken());
        }

        return $this->getRoot();
    }

    /**
     * Prepares the parser for parsing
     *
     * @param TokenStream $stream
     * @return void
     */
    public function prepare(TokenStream $stream) :void
    {
        $this->root = $this->scope = new RootNode();
        $this->stream = $stream;
        $this->lastTokenIndex = max(0, count($this->stream->getTokens()) - 1);
        $this->cursor = 0;
    }

    /**
     * Parses outside of tags
     *
     * @return void
     */
    public function parseOutsideTag() : void
    {
        if (!count($this->stream->getTokens())) {
            return;
        }

        if ($this->accept(Token::T_TEXT)) {
            $this->insert(new TextNode($this->getCurrentToken()->getValue()));
            $this->advance();
        }

        if ($this->skip(Token::T_OPENING_TAG)) {
            $this->parseStatement();
        }
    }

    /**
     * Parses text
     *
     * @return void
     */
    public function parseText() : void
    {
        if (!count($this->stream->getTokens())) {
            return;
        }

        if ($this->accept(Token::T_TEXT)) {
            $this->insert(new TextNode($this->getCurrentToken()->getValue()));
            $this->advance();
        }
    }

    /**
     * Parses a statement
     *
     * @return void
     */
    private function parseStatement() : void
    {
        $this->runtime->getNodeCollection()->parse($this);
    }

    /**
     * Expects the current token to be of given type and optionally has given value
     *
     * @param int $type
     * @param null $value
     * @return bool
     * @throws ParseError
     */
    public function expect(int $type, $value = null) : bool
    {
        if (!$this->accept($type, $value)) {
            $this->syntaxError('Expected '.strtoupper($type.' '.$value).' got '.$this->getCurrentToken());
        }

        return true;
    }

    /**
     * Expects the next token to be of given type and optionally has given value
     *
     * @param int $type
     * @param null $value
     * @return bool
     * @throws ParseError
     */
    public function expectNext(int $type, $value = null) : bool
    {
        if (!$this->acceptNext($type, $value)) {
            throw new ParseError('Expected '.strtoupper($type.' '.$value).' got '.$this->getCurrentToken(), $this->getCurrentToken()->getLine());
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
    public function skip(int $type, $value = null) : bool
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
    public function accept(int $type, $value = null) : bool
    {
        if ($this->getCurrentToken()->getType() === $type) {
            if ($value) {
                if ($this->getCurrentToken()->getValue() === $value) {
                    return true;
                }

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Accepts the next token if it's of given type and optionally has given value
     *
     * @param int $type
     * @param null $value
     * @return bool
     */
    public function acceptNext(int $type, $value = null) : bool
    {
        if ($this->getNextToken()->getType() === $type) {
            if ($value) {
                if ($this->getNextToken()->getValue() === $value) {
                    return true;
                }

                return false;
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
    public function getCurrentToken() : Token
    {
        return $this->stream->getToken($this->cursor);
    }

    /**
     * Gets the token at the next position from the cursor
     *
     * @return Token
     */
    public function getNextToken() : Token
    {
        return $this->stream->getToken($this->cursor + 1);
    }

    /**
     * Sets the parsing scope
     *
     * @param Node $scope
     * @return void
     */
    public function setScope(Node $scope) : void
    {
        $this->scope = $scope;
    }

    /**
     * Gets the parsing scope
     *
     * @return Node
     * @throws AegisError
     */
    public function getScope() : Node
    {
        if (!$this->scope) {
            throw new AegisError('No scope available before parsing');
        }

        return $this->scope;
    }

    /**
     * Gets the root node (full AST)
     *
     * @return RootNode
     * @throws AegisError
     */
    public function getRoot() : RootNode
    {
        if (!$this->root) {
            throw new AegisError('No root node available before parsing');
        }

        return $this->root;
    }

    /**
     * Moves inside the last inserted node
     *
     * @return void
     */
    public function traverseUp() : void
    {
        $this->setScope($this->getScope()->getLastChild());
    }

    /**
     * Moves outside the current scope
     *
     * @return void
     * @throws ParseError
     */
    public function traverseDown() : void
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
    public function advance() : void
    {
        if ($this->cursor < count($this->stream->getTokens()) - 1) {
            ++$this->cursor;
        }
    }

    /**
     * Moves to the root node
     *
     * @return void
     */
    public function root() : void
    {
        $this->setScope($this->root);
    }

    /**
     * Wraps the last inserted node with the given node
     *
     * @param Node $node
     * @return void
     */
    public function wrap(Node $node) : void
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
     * @return void
     */
    public function setAttribute() : void
    {
        $last = $this->scope->getLastChild(); // Get the last inserted node
        $this->scope->removeLastChild(); // Remove it
        $this->scope->setAttribute($last);
    }

    /**
     * Inserts the given node
     *
     * @param Node $node
     * @return void
     */
    public function insert(Node $node) : void
    {
        $node->setParent($this->scope);
        $this->scope->insert($node);
    }

    /**
     * @param string $message
     * @return void
     * @throws SyntaxError
     */
    public function syntaxError(string $message = '') : void
    {
        throw new SyntaxError($message, $this->getCurrentToken()->getLine(), $this->getCurrentToken()->getPosition());
    }
}
