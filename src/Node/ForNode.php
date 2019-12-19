<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

class ForNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_IDENT, 'for')) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if ($parser->accept(TokenType::T_VAR)) {

                // T_VAR as first attribute

                $parser->insert(new VariableNode($parser->getCurrentToken()->getValue()));
                $parser->setAttribute('item');
                $parser->advance();

                $parser->expect(TokenType::T_IDENT, 'in');
                $parser->advance();

                ExpressionNode::parse($parser);
                $parser->setAttribute('arrayable');
            } elseif ($parser->accept(TokenType::T_NUMBER)) {

                // T_NUMBER as first attribute

                $parser->insert(new NumberNode($parser->getCurrentToken()->getValue()));
                $parser->setAttribute('item');
                $parser->advance();

                $parser->expect(TokenType::T_IDENT, 'to');
                $parser->advance();

                $checkForAlias = false;

                if ($parser->accept(TokenType::T_NUMBER)) {
                    $parser->insert(new NumberNode($parser->getCurrentToken()->getValue()));
                    $parser->setAttribute('arrayable');
                    $parser->advance();
                    $checkForAlias = true;
                } elseif ($parser->accept(TokenType::T_VAR)) {
                    $parser->insert(new VariableNode($parser->getCurrentToken()->getValue()));
                    $parser->setAttribute('arrayable');
                    $parser->advance();
                    $checkForAlias = true;
                }

                if ($checkForAlias && $parser->accept(TokenType::T_IDENT, 'as')) {
                    $parser->advance();
                    $parser->expect(TokenType::T_VAR);
                    $parser->insert(new VariableNode($parser->getCurrentToken()->getValue()));
                    $parser->setAttribute('alias');
                    $parser->advance();
                }
            }

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();

            $parser->parseOutsideTag();

            $parser->expect(TokenType::T_IDENT, '/for');
            $parser->advance();
            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();

            $parser->traverseDown();
            $parser->parseOutsideTag();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $loopitem = $this->getAttribute('item');
        $arrayable = $this->getAttribute('arrayable');

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
            $compiler->write('<?php for(');
            $compiler->write('$i');
            $compiler->write(' = ');
            $loopitem->compile($compiler);
            $compiler->write('; ');
            $compiler->write('$i');
            $compiler->write(' <= ');
            $arrayable->compile($compiler);
            $compiler->write('; ');
            $compiler->write('$i');
            $compiler->write('++): ?>');

            $alias = $this->getAttribute('alias');

            if ($alias) {
                $compiler->write('<?php $env->set(\''.$alias->getVariableName().'\', $i); ?>');
            }

            foreach ($this->getChildren() as $c) {
                $c->compile($compiler);
            }

            $compiler->write('<?php endfor; ?>');
        }
    }
}
