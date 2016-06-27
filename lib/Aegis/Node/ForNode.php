<?php

namespace Aegis\Node;

use Aegis\Token;

class ForNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'for' ) ) {
			
			$parser->traverseUp();
			$parser->expect( Token::T_VAR );
			$parser->setAttribute();

			$parser->skip( Token::T_IDENT, 'in' );

			$parser->expect( Token::T_VAR );
			$parser->setAttribute();

			$parser->skip( Token::T_CLOSING_TAG );

			$parser->parseOutsideTag();

			$parser->skip( Token::T_OPENING_TAG );
			$parser->skip( Token::T_IDENT, '/for' );
			$parser->skip( Token::T_CLOSING_TAG );

			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$loopitem = $this->getAttribute( 0 );
		$arrayable = $this->getAttribute( 1 );
		
		$compiler->write( '<?php foreach(' );
		$arrayable->compile( $compiler );
		$compiler->write( ' as ' );
		$loopitem->compile( $compiler ); // @TODO this variable possibly overrides another and is globally avialable in the template, attempt to fix this!
		$compiler->write( '): ?>' );
		
		foreach( $this->getChildren() as $c )
		{
			$c->compile( $compiler );
		}
		
		$compiler->write( '<?php endforeach; ?>' );
	}
}