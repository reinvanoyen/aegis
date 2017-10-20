<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class OperatorNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class OperatorNode extends Node
{
	/**
	 * @var string
	 */
    private $type;

	/**
	 * @var bool
	 */
    private $numberMath;

	/**
	 * OperatorNode constructor.
	 * @param $type
	 * @param bool $numberMath
	 */
    public function __construct($type, $numberMath=false)
    {
        $this->type = $type;
        $this->numberMath = $numberMath;
    }

    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_OP)) {
            if (($parser->getCurrentToken()->getValue() === '+' && $parser->getScope()->getLastChild() instanceof NumberNode) &&
                $parser->getNextToken()->getType() === Token::T_NUMBER
            ) {
                $parser->insert(new static($parser->getCurrentToken()->getValue(), true));
                $parser->advance();
            } else {
                $parser->insert(new static($parser->getCurrentToken()->getValue()));
                $parser->advance();
            }

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        if ($this->type === '+') {

            if ($this->numberMath) {

                $compiler->write(' + ');
            } else {
                $compiler->write(' . ');
            }

        } elseif ($this->type === '?') {

            $compiler->write(' ?: ');

        } else {

            $compiler->write(' '.$this->type.' ');
        }
    }
}