<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class VariableNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class VariableNode extends Node
{
    private $variableName;

    /**
     * VariableNode constructor.
     * @param string $variableName
     */
    public function __construct(string $variableName)
    {
        $this->variableName = $variableName;
    }

    /**
     * @return string
     */
    public function getVariableName() : string
    {
        return $this->variableName;
    }

    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_VAR)) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler, $local = false)
    {
        if ($local) {
            $compiler->write('$'.str_replace('.', '->', $this->getVariableName()));
        } else {
            $compiler->write('$env->'.str_replace('.', '->', $this->getVariableName()));
        }
    }
}
