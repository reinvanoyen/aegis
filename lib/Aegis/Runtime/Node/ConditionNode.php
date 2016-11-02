<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;
use Aegis\Node;

class ConditionNode extends Node
{
	public static function parse($parser)
	{
		if (
			ExpressionNode::parse($parser) ||
			LogicalOperatorNode::parse($parser)
		) {
			if (!$parser->getScope() instanceof self) {

				// Insert the condition and move inside
				$parser->wrap(new static());
			}

			if(
				ExpressionNode::parse($parser) ||
				LogicalOperatorNode::parse($parser)
			) {
				self::parse($parser);
			} else {
				$parser->traverseDown();
			}

			return true;
		} else {

			// Get out of the condition if we are still in it
			if ($parser->getScope() instanceof self) {
				$parser->traverseDown();
			}
		}

		return false;
	}

	public function compile($compiler)
	{
		foreach ($this->getChildren() as $c) {
			$c->compile($compiler);
		}
	}
}