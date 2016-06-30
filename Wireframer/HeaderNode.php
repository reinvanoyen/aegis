<?php

namespace Wireframer;

use Aegis\Token;
use Aegis\Node\Node;

class HeaderNode extends Node
{
	private $tag;

	public function __construct( $tag )
	{
		$this->tag = $tag;
	}

	public static function parse( $parser )
	{
		if(
			$parser->accept( Token::T_IDENT, 'h1' ) ||
			$parser->accept( Token::T_IDENT, 'h2' ) ||
			$parser->accept( Token::T_IDENT, 'h3' ) ||
			$parser->accept( Token::T_IDENT, 'h4' ) ||
			$parser->accept( Token::T_IDENT, 'h5' ) ||
			$parser->accept( Token::T_IDENT, 'h6' )
		) {

			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();

			$parser->traverseUp();
			$parser->parseAttribute();
			$parser->skip( Token::T_CLOSING_TAG );
			$parser->traverseDown();
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<' . $this->tag . '>' );
		$compiler->write( '<?php echo ' );
		foreach( $this->getAttributes() as $a ) {

			$a->compile( $compiler );
		}
		$compiler->write( '; ?>' );
		$compiler->write( '</' . $this->tag . '>' );
	}
}