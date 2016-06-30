<?php

namespace Wireframer;

use Aegis\Token;
use Aegis\Node\Node;

class ButtonNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'button' ) ) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->traverseUp();
			$parser->parseAttribute();
			$parser->skip( Token::T_CLOSING_TAG );
			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<button>' );
		$compiler->write( '<?php echo ' );

		foreach( $this->getAttributes() as $a ) {

			$a->compile( $compiler );
		}

		$compiler->write( '; ?>' );
		$compiler->write( '</button>' );
	}
}