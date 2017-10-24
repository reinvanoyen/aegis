<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class ConstantNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ConstantNode extends Node
{
    private $value;

    public function __construct($value)
    {
	    switch ($value) {
		    case 'zero':
			    $value = 0;
			    break;
		    case 'pi':
			    $value = M_PI;
			    break;
	    }

	    $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function parse(ParserInterface $parser)
    {
        if (
            $parser->accept(Token::T_IDENT, 'false') ||
            $parser->accept(Token::T_IDENT, 'true') ||
            $parser->accept(Token::T_IDENT, 'null') ||
            $parser->accept(Token::T_IDENT, 'zero') ||
            $parser->accept(Token::T_IDENT, 'pi')
        ) {
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
