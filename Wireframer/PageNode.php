<?php

namespace Wireframer;

use Aegis\Token;
use Aegis\Node\Node;
use Aegis\Compiler;

class PageNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'page' ) ) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->traverseUp();
			$parser->parseAttribute();

			$parser->skip( Token::T_CLOSING_TAG );

			$parser->parseOutsideTag();

			$parser->skip( Token::T_OPENING_TAG );
			$parser->skip( Token::T_IDENT, '/page' );
			$parser->skip( Token::T_CLOSING_TAG );

			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$nameAttr = $this->getAttribute( 0 );
		$subcompiler = new Compiler( $nameAttr );
		$name = $subcompiler->compile();

		$compiler->write( '<div class="page" style="background-color: #f0f0f0; padding: 30px;; border-radius: 5px; box-shadow: 0 2px 3px #eee;">' );

		foreach( $this->getChildren() as $c ) {

			$c->compile( $compiler );
		}

		$compiler->write( '</div>' );
	}
}