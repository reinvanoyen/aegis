<?php

namespace Aegis\Node;

use Aegis\Token;

class PrintNode extends \Aegis\Node
{
	public static function parse( $parser )
	{
		if( ExpressionNode::parse( $parser ) ) {

			$parser->wrap( new static() );
			$parser->traverseDown();
		}

		if( $parser->skip( Token::T_CLOSING_TAG ) ) {

			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<?php echo htmlspecialchars( ' );

		foreach( $this->getChildren() as $c ) {

			$c->compile( $compiler );
		}

		$compiler->write( ' ); ?>' );
	}
}