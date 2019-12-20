<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class PropNode
 * @package Aegis\Node
 */
class PropNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if (
            $parser->accept(TokenType::T_IDENT, 'prop') ||
            $parser->accept(TokenType::T_IDENT, 'property')
        ) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if (!ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token' . $parser->getCurrentToken() . ', expected expression');
            }
            $parser->setAttribute('propName');

            if (ExpressionNode::parse($parser)) {
                $parser->setAttribute('propValue');
            }

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();
            $parser->traverseDown();
            $parser->parseOutsideTag();
        }
    }

    public function compile(CompilerInterface $compiler)
    {
        $propNameAttribute = $this->getAttribute('propName');
        $subcompiler = $compiler->clone();
        $propName = $subcompiler->compile($propNameAttribute);

        // Write head of itself
        $compiler->head('<?php $env->setBlock(');
        $compiler->head($propName);
        $compiler->head(', function() use ($env, $tpl) { ?>');

        if ($this->getAttribute('propValue')) {

            $subcompiler = $compiler->clone();
            $propValue = $subcompiler->compile($this->getAttribute('propValue'));

            $compiler->head('<?php echo htmlspecialchars(');
            $compiler->head($propValue);
            $compiler->head('); ?>');
        }

        $compiler->head('<?php }); ?>');

        // Render itself
        $compiler->write('<?php $env->getBlock( ');
        $compiler->write($propName);
        $compiler->write('); ?>');
    }
}
