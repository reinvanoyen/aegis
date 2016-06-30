<?php

namespace Wireframer;

use Aegis\Node\Expression;
use Aegis\Token;
use Aegis\Node\Node;

class NavNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'nav' ) ) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->traverseUp();

			$parser->skip( Token::T_CLOSING_TAG );

			$parser->parseOutsideTag();

			$parser->skip( Token::T_OPENING_TAG );
			$parser->skip( Token::T_IDENT, '/nav' );
			$parser->skip( Token::T_CLOSING_TAG );

			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<ul>' );

		foreach( $this->getChildren() as $c ) {

			if( $c instanceof Expression ) {

				$compiler->write( '<li><button>' );
			}

			$c->compile( $compiler );

			if( $c instanceof Expression ) {

				$compiler->write( '</button></li>' );
			}
		}
		
		$compiler->write( '</ul>' );
	}
}