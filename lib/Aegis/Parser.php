<?php

namespace Aegis;

use Aegis\Node\RootNode;
use Aegis\Node\TextNode;

class Parser implements ParserInterface
{
    private $runtime;

    private $root;
    private $scope;

    private $tokenStream;
    private $cursor;
    private $lastTokenIndex;

    public function setRuntime(RuntimeInterface $runtime)
    {
        $this->runtime = $runtime;
    }

    public function parse(TokenStream $stream)
    {
        if (! $this->runtime) {
            throw new AegisError('Runtime needs to be set before parsing');
        }

        $this->root = $this->scope = new RootNode();
        $this->cursor = 0;
        $this->tokenStream = $stream;
        $this->lastTokenIndex = count($this->tokenStream->getTokens()) - 1;

        $this->parseOutsideTag();

        return $this->getRoot();
    }

    public function parseOutsideTag()
    {
        if (!count($this->tokenStream->getTokens())) {
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

    private function parseStatement()
    {
        $this->runtime->getNodeCollection()->parse($this);
    }

    public function expect($type, $value = null)
    {
        if (!$this->accept($type, $value)) {
            throw new ParseError('Expected '.strtoupper($type.' '.$value).' got '.$this->getCurrentToken(), $this->getCurrentToken()->getLine());
        }

        return true;
    }

    public function expectNext($type, $value = null)
    {
        if (!$this->acceptNext($type, $value)) {
            throw new ParseError('Expected '.strtoupper($type.' '.$value).' got '.$this->getCurrentToken(), $this->getCurrentToken()->getLine());
        }

        return true;
    }

    public function skip($type, $value = null)
    {
        if ($this->accept($type, $value)) {
            $this->advance();

            return true;
        }

        return false;
    }

    public function accept($type, $value = null)
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

    public function acceptNext($type, $value = null)
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

    public function getCurrentToken()
    {
        return $this->tokenStream->getToken($this->cursor);
    }

    public function getNextToken()
    {
        return $this->tokenStream->getToken($this->cursor + 1);
    }

    public function setScope(Node $scope)
    {
        $this->scope = $scope;
    }

    public function getScope()
    {
        if (!$this->scope) {
            throw new AegisError('No scope available before parsing');
        }

        return $this->scope;
    }

    public function getRoot()
    {
        if (!$this->root) {
            throw new AegisError('No root node available before parsing');
        }

        return $this->root;
    }

    public function traverseUp()
    {
        $this->setScope($this->getScope()->getLastChild());
    }

    public function traverseDown()
    {
        try {
            $parent = $this->getScope()->getParent();
        } catch (AegisError $e) {
            throw new ParseError($e->getMessage());
        }

        $this->setScope($parent);
    }

    public function advance()
    {
        if ($this->cursor < count($this->tokenStream->getTokens()) - 1) {
            ++$this->cursor;
        }
    }

    public function root()
    {
        $this->setScope($this->root);
    }

    public function wrap(Node $node)
    {
        $last = $this->scope->getLastChild(); // Get the last inserted node
        $this->scope->removeLastChild(); // Remove it

        $this->insert($node);
        $this->traverseUp();

        $this->insert($last);
    }

    public function setAttribute()
    {
        $last = $this->scope->getLastChild(); // Get the last inserted node
        $this->scope->removeLastChild(); // Remove it
        $this->scope->setAttribute($last);
    }

    public function insert(Node $node)
    {
        $node->setParent($this->scope);
        $this->scope->insert($node);
    }
}
