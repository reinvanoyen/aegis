<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

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

            if ($parser->expect(TokenType::T_CLOSING_TAG)) {
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
