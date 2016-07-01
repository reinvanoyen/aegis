<?php

namespace Wireframer;

use Aegis\Node\OptionNode;
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

			if( $parser->accept( Token::T_IDENT, 'vertical' ) || $parser->accept( Token::T_IDENT, 'horizontal' ) ) {

				$parser->insert( new OptionNode( $parser->getCurrentToken()->getValue() ) );
				$parser->setAttribute();
				$parser->advance();
			}

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
		$direction = 'vertical';

		if( $this->getAttribute( 0 ) ) {

			$direction = $this->getAttribute( 0 )->getValue();
		}

		$compiler->write( '<div class="box ' . $direction . '">' );

		foreach( $this->getChildren() as $c ) {

			$c->compile( $compiler );
		}
		
		$compiler->write( '</div>' );
	}
}