<?php

namespace Aegis;

class Compiler implements CompilerInterface
{
    private $input;

    private $head = '';
    private $body = '';

    public function __construct(Node $input)
    {
        $this->input = $input;
    }

    public function compile()
    {
        $this->input->compile($this);

        return $this->getHead().$this->getBody();
    }

    public function getHead()
    {
        return $this->head;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function head($string)
    {
        $this->head .= $string;
    }

    public function write($string)
    {
        $this->body .= $string;
    }
}