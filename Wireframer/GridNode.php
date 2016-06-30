<?php

namespace Wireframer;

use Aegis\Node\Expression;
use Aegis\Node\Number;
use Aegis\Node\TextNode;
use Aegis\Token;
use Aegis\Node\Node;

class GridNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'grid' ) ) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->traverseUp();

			if( $parser->accept( Token::T_NUMBER ) ) {

				$parser->insert( new Number( $parser->getCurrentToken()->getValue() ) );
				$parser->setAttribute();
				$parser->advance();
			}

			$parser->skip( Token::T_CLOSING_TAG );

			$parser->parseOutsideTag();

			$parser->skip( Token::T_OPENING_TAG );
			$parser->skip( Token::T_IDENT, '/grid' );
			$parser->skip( Token::T_CLOSING_TAG );

			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<div class="grid" style="display: flex; flex-wrap: wrap; margin: 0 -15px;">' );

		$width = 50;

		if( $this->getAttribute( 0 ) ) {

			$amount = $this->getAttribute( 0 )->getValue();
			$width = 100 / $amount;
		}

		foreach( $this->getChildren() as $c ) {

			if( ! $c instanceof TextNode ) {

				$compiler->write( '<div class="grid-item" style="box-sizing: border-box; width: ' . $width . '%; flex-grow: 1; display: flex; padding: 0 15px;">' );
			}

			$c->compile( $compiler );

			if( ! $c instanceof TextNode ) {

				$compiler->write( '</div>' );
			}
		}

		$compiler->write( '</div>' );
	}
}