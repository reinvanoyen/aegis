<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class IncludeNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class IncludeNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_IDENT, 'include')) {
            $parser->insert(new static());
            $parser->traverseUp();
            $parser->advance();

            if (! ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token ' . $parser->getCurrentToken());
            }
            $parser->setAttribute('filename');

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();
            $parser->traverseDown();
            $parser->parseOutsideTag();
        }
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php echo $tpl->render(');
        $this->getAttribute('filename')->compile($compiler);
        $compiler->write('); ?>');
    }
}
