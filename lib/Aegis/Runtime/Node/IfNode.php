<?php

namespace Aegis\Runtime\Node;

use Aegis\Parser;
use Aegis\Token;

class IfNode extends \Aegis\Node
{
	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'if' ) ) {

			$parser->insert( new static() );
			$parser->advance();
			$parser->traverseUp();

			ExpressionNode::parse( $parser );
			$parser->setAttribute();

			$parser->skip( Token::T_CLOSING_TAG );

			$parser->parseOutsideTag();

			if( ElseNode::parse( $parser ) ) {

				$parser->parseOutsideTag();
			}

			$parser->skip( Token::T_OPENING_TAG );
			$parser->skip( Token::T_IDENT, '/if' );
			$parser->skip( Token::T_CLOSING_TAG );

			$parser->traverseDown();
			$parser->parseOutsideTag();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( $compiler )
	{
		$compiler->write('<?php if( ' );

		foreach( $this->getAttributes() as $a ) {

			$a->compile( $compiler );
		}

		$compiler->write( ' ): ?>');
		
		foreach( $this->getChildren() as $c ) {

			$c->compile( $compiler );
		}

		$compiler->write( '<?php endif; ?>' );
	}
}
