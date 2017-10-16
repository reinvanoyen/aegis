<?php

namespace Aegis;

/**
 * Class Compiler
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class Compiler implements CompilerInterface
{
    /**
     * @var string
     */
    private $head = '';

    /**
     * @var string
     */
    private $body = '';

    /**
     * Compiles a node into a string
     *
     * @param Node $node
     * @return string
     */
    public function compile(Node $node) : string
    {
        $this->head = $this->body = '';
        $node->compile($this);
        return $this->getResult();
    }

    /**
     * Gets the result of the compiling as a string
     *
     * @return string
     */
    public function getResult() : string
    {
        return $this->getHead().$this->getBody();
    }

    /**
     * @return string
     */
    public function getHead() : string
    {
        return $this->head;
    }

    /**
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * Writes a string to the head
     *
     * @param $string
     */
    public function head($string)
    {
        $this->head .= $string;
    }

    /**
     * Writes a string to the body
     *
     * @param $string
     * @return void
     */
    public function write(string $string) : void
    {
        $this->body .= $string;
    }
}
