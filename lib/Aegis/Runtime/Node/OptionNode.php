<?php

namespace Aegis\Runtime\Node;

use Aegis\Node;

class OptionNode extends Node
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function compile($compiler)
    {
    }
}
