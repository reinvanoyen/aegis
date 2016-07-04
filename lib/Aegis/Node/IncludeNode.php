<?php

namespace Aegis\Node;

use Aegis\Token;

class IncludeNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'include' ) ) {

			$parser->insert( new static() );
			$parser->traverseUp();
			$parser->advance();

			ExpressionNode::parse( $parser );
			$parser->setAttribute();

			$parser->skip( Token::T_CLOSING_TAG );
			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<?php $this->render(' );

		foreach( $this->getAttributes() as $a ) {

			$a->compile( $compiler );
		}

		$compiler->write( '); ?>' );
	}
}