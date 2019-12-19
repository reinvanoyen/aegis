<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class LogicalOperatorNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class LogicalOperatorNode extends Node
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public static function parse(ParserInterface $parser)
    {
        if (
            $parser->accept(TokenType::T_IDENT, 'or') ||
            $parser->accept(TokenType::T_IDENT, 'and') ||
            $parser->accept(TokenType::T_IDENT, 'equals')
        ) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        if ($parser->accept(TokenType::T_IDENT, 'not')) {
            if ($parser->acceptNext(TokenType::T_IDENT, 'equals')) {
                $parser->insert(new static('neq'));
                $parser->advance();
                $parser->advance();

                return true;
            }

            $parser->insert(new static('not'));
            $parser->advance();
            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        switch ($this->type) {
            case 'neq':
                $compiler->write(' !== ');
                break;
            case 'or':
                $compiler->write(' || ');
                break;
            case 'not':
                $compiler->write(' ! ');
                break;
            case 'and':
                $compiler->write(' && ');
                break;
            case 'equals':
                $compiler->write(' === ');
                break;
        }
    }
}
