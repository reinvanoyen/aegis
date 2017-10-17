<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class IncludeNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class IncludeNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'include')) {
            $parser->insert(new static());
            $parser->traverseUp();
            $parser->advance();

            if (! ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token ' . $parser->getCurrentToken());
            }
            $parser->setAttribute();

            $parser->expect(Token::T_CLOSING_TAG);
            $parser->advance();
            $parser->traverseDown();
            $parser->parseOutsideTag();
        }
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php echo $tpl->render(');

        foreach ($this->getAttributes() as $a) {
            $a->compile($compiler);
        }

        $compiler->write('); ?>');
    }
}
