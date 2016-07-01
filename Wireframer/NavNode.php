<?php

namespace Wireframer;

use Aegis\Node\Expression;
use Aegis\Node\OptionNode;
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

			if( $parser->accept( Token::T_IDENT, 'vertical' ) || $parser->accept( Token::T_IDENT, 'horizontal' ) ) {

				$parser->insert( new OptionNode( $parser->getCurrentToken()->getValue() ) );
				$parser->setAttribute();
				$parser->advance();
			}

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
		$direction = 'vertical';

		if( $this->getAttribute( 0 ) ) {

			$direction = $this->getAttribute( 0 )->getValue();
		}

		$compiler->write( '<ul class="nav ' . $direction . '">' );

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