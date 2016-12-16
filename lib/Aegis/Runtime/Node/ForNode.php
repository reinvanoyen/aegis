<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class ForNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'for')) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if ($parser->accept(Token::T_VAR)) {

                // T_VAR as first attribute

                $parser->insert(new VariableNode($parser->getCurrentToken()->getValue()));
                $parser->setAttribute();
                $parser->advance();

                $parser->expect(Token::T_IDENT, 'in');
                $parser->advance();

                ExpressionNode::parse($parser);
                $parser->setAttribute();
            } elseif ($parser->accept(Token::T_NUMBER)) {

                // T_NUMBER as first attribute

                $parser->insert(new NumberNode($parser->getCurrentToken()->getValue()));
                $parser->setAttribute();
                $parser->advance();

                $parser->expect(Token::T_IDENT, 'to');
                $parser->advance();

                if ($parser->expect(Token::T_NUMBER)) {
                    $parser->insert(new NumberNode($parser->getCurrentToken()->getValue()));
                    $parser->setAttribute();
                    $parser->advance();

                    if ($parser->accept(Token::T_IDENT, 'as')) {
                        $parser->advance();
                        $parser->expect(Token::T_VAR);
                        $parser->insert(new VariableNode($parser->getCurrentToken()->getValue()));
                        $parser->setAttribute();
                        $parser->advance();
                    }
                }
            }

            $parser->expect(Token::T_CLOSING_TAG);
	        $parser->advance();

            $parser->parseOutsideTag();

	        $parser->expect(Token::T_IDENT, '/for');
            $parser->advance();
            $parser->expect(Token::T_CLOSING_TAG);
            $parser->advance();

            $parser->traverseDown();
            $parser->parseOutsideTag();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $loopitem = $this->getAttribute(0);
        $arrayable = $this->getAttribute(1);

        if ($loopitem instanceof VariableNode) {
            $compiler->write('<?php foreach(');
            $arrayable->compile($compiler);
            $compiler->write(' as ');
            $loopitem->compile($compiler);
            $compiler->write('): ?>');

            foreach ($this->getChildren() as $c) {
                $c->compile($compiler);
            }

            $compiler->write('<?php endforeach; ?>');
            $compiler->write('<?php unset(');
            $loopitem->compile($compiler);
            $compiler->write('); ?>');
        } elseif ($loopitem instanceof NumberNode) {
            $loopvar = null;
            if ($this->getAttribute(2)) {
                $loopvar = $this->getAttribute(2);
            }

            $compiler->write('<?php for( ');
            $compiler->write('$i');
            $compiler->write(' = ');
            $loopitem->compile($compiler);
            $compiler->write('; ');
            $compiler->write('$i');
            $compiler->write(' <= ');
            $arrayable->compile($compiler);
            $compiler->write('; ');
            $compiler->write('$i');
            $compiler->write('++ ): ?>');

            if ($loopvar !== null) {
                $compiler->write('<?php $env->set( \''.$loopvar->getName().'\', $i ); ?>');
            }

            foreach ($this->getChildren() as $c) {
                $c->compile($compiler);
            }

            $compiler->write('<?php endfor; ?>');
        }
    }
}
