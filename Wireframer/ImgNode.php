<?php

namespace Wireframer;

use Aegis\Token;
use Aegis\Node\Node;

class ImgNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'img' ) ) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->traverseUp();
			$parser->skip( Token::T_CLOSING_TAG );

			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<div class="img"></div>' );
	}
}