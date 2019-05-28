<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class StringNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class StringNode extends Node
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_STRING)) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('\''.$this->value.'\'');
    }
}
