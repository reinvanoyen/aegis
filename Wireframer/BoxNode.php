<?php

namespace Wireframer;

use Aegis\Token;
use Aegis\Node\Node;

class BoxNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'box' ) ) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->traverseUp();

			$parser->skip( Token::T_CLOSING_TAG );

			$parser->parseOutsideTag();

			$parser->skip( Token::T_OPENING_TAG );
			$parser->skip( Token::T_IDENT, '/box' );
			$parser->skip( Token::T_CLOSING_TAG );

			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<div class="box">' );

		foreach( $this->getChildren() as $c ) {

			$c->compile( $compiler );
		}
		
		$compiler->write( '</div>' );
	}
}