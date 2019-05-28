<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class NumberNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class NumberNode extends Node
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

    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_NUMBER)) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write($this->getValue());
    }
}
