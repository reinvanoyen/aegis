<?php

namespace Aegis\Runtime\Node;

use Aegis\Compiler;
use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class BlockNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'block')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->traverseUp();

            ExpressionNode::parse($parser);
            $parser->setAttribute();

            if ($parser->accept(Token::T_IDENT, 'prepend') || $parser->accept(Token::T_IDENT, 'append')) {
                $parser->insert(new OptionNode($parser->getCurrentToken()->getValue()));
                $parser->setAttribute();
                $parser->advance();
            }

            $parser->expect(Token::T_CLOSING_TAG);
            $parser->advance();

            $parser->parseOutsideTag();

            $parser->expect(Token::T_IDENT, '/block');
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
        $nameAttr = $this->getAttribute(0);
        $subcompiler = new Compiler();
        $name = $subcompiler->compile($nameAttr);

        // Determine which function to use
        $blockHeadFunction = 'setBlock';

        if ($this->getAttribute(1)) {
            $optionAttr = $this->getAttribute(1);

            if ($optionAttr->getValue() === 'prepend') {
                $blockHeadFunction = 'prependBlock';
            } elseif ($optionAttr->getValue() === 'append') {
                $blockHeadFunction = 'appendBlock';
            }
        }

        // Write the heads of the children first
        foreach ($this->getChildren() as $c) {
            $subcompiler = new Compiler();
            $subcompiler->compile($c);
            $compiler->head($subcompiler->getHead());
        }

        // Write head of itself
        $compiler->head('<?php $env->'.$blockHeadFunction.'( ');
        $compiler->head($name);
        $compiler->head(', function() use ( $env, $tpl ) { ?>');

        foreach ($this->getChildren() as $c) {
            $subcompiler = new Compiler();
            $subcompiler->compile($c);
            $compiler->head($subcompiler->getBody());
        }

        $compiler->head('<?php } ); ?>');

        // Render itself
        $compiler->write('<?php $env->getBlock( ');
        $compiler->write($name);
        $compiler->write('); ?>');
    }
}
