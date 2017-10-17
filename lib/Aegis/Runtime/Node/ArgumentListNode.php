<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class ArgumentListNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ArgumentListNode extends Node
{
	/**
	 * @param ParserInterface $parser
	 * @return bool
	 */
    public static function parse(ParserInterface $parser)
    {
        $parser->insert(new static());
        $parser->traverseUp();

        if (ExpressionNode::parse($parser)) {
            if ($parser->skip(Token::T_SYMBOL, ',')) {
                self::parse($parser);
            }
        }

        $parser->traverseDown();

        return true;
    }

	/**
	 * Compiles the node
	 *
	 * @param CompilerInterface $compiler
	 */
    public function compile(CompilerInterface $compiler)
    {
        $i = 0;

        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);

            ++$i;

            if ($i < count($this->getChildren())) {
                $compiler->write(', ');
            }
        }
    }
}
