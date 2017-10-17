<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class PrintNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class PrintNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if (ExpressionNode::parse($parser)) {
            $parser->wrap(new static());
            $parser->traverseDown();

            if ($parser->expect(Token::T_CLOSING_TAG)) {
                $parser->advance();
                $parser->parseOutsideTag();
            }
        }
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php echo htmlspecialchars(');

        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }

        $compiler->write('); ?>');
    }
}
