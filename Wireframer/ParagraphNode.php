<?php

namespace Wireframer;

use Aegis\Node\Number;
use Aegis\Token;
use Aegis\Node\Node;

class ParagraphNode extends Node
{
	public static function parse( $parser )
	{
		if(
			$parser->accept( Token::T_IDENT, 'p' ) ||
			$parser->accept( Token::T_IDENT, 'paragraph' )
		) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->traverseUp();

			if( $parser->accept( Token::T_NUMBER ) ) {

				$parser->insert( new Number( $parser->getCurrentToken()->getValue() ) );
				$parser->setAttribute();
				$parser->advance();
			}

			$parser->skip( Token::T_CLOSING_TAG );
			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$words = [
			'lorem',
			'ipsum',
			'doler',
			'sit',
			'amet',
			'consectetur',
			'adipiscing',
			'elit',
			'tempor',
			'odio',
		];
		
		$generated = '';
		
		$compiler->write( '<p>' );
		
		if( $this->getAttribute( 0 ) ) {

			$len = $this->getAttribute( 0 )->getValue();

			while( strlen( $generated ) < $len ) {

				$k = array_rand( $words );
				$word = $words[ $k ];

				$generated .= ' ' . $word;

				if( strlen( $generated ) >= $len ) {

					$compiler->write( ucfirst( trim( $generated ) ) . '.' );
				}
			}
		}

		$compiler->write( '</p>' );
	}
}