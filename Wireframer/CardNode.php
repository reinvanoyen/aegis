<?php

namespace Wireframer;

use Aegis\Token;
use Aegis\Node\Node;
use Aegis\Compiler;

class CardNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'card' ) ) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->traverseUp();

			$parser->skip( Token::T_CLOSING_TAG );

			$parser->parseOutsideTag();

			$parser->skip( Token::T_OPENING_TAG );
			$parser->skip( Token::T_IDENT, '/card' );
			$parser->skip( Token::T_CLOSING_TAG );

			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<div class="card" style="background-color: #ffffff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 3px #eee;">' );

		foreach( $this->getChildren() as $c ) {

			$c->compile( $compiler );
		}

		$compiler->write( '</div>' );
	}
}