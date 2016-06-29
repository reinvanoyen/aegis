<?php

namespace Aegis\Node;

use Aegis\Token;

class RawNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'raw' ) || $parser->accept( Token::T_IDENT, 'r' ) ) {

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
		$compiler->write( '<?php echo ' );

		foreach( $this->getAttributes() as $a )
		{
			$a->compile( $compiler );
		}

		$compiler->write( '; ?>' );
	}
}