<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class AssignmentNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class AssignmentNode extends Node
{
	public static function parse(ParserInterface $parser)
	{
		if ($parser->accept(Token::T_IDENT, 'set')) {
			$parser->insert(new static());
			$parser->advance();
			$parser->traverseUp();

			if (! VariableNode::parse($parser)) {
				$parser->syntaxError('Unexpected token' . $parser->getCurrentToken() . ', expected variable');
			}
			$parser->setAttribute();

			if (! ExpressionNode::parse($parser)) {
				$parser->syntaxError('Unexpected token' . $parser->getCurrentToken());
			}

			$parser->expect(Token::T_CLOSING_TAG);
			$parser->advance();
			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile(CompilerInterface $compiler)
	{
		$compiler->write('<?php ');

		foreach ($this->getAttributes() as $a) {
			$a->compile($compiler);
		}

		$compiler->write(' = ');

		foreach ($this->getChildren() as $c) {
			$c->compile($compiler);
		}

		$compiler->write('; ?>');
	}
}
