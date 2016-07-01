<?php

namespace Aegis\Node;

use Aegis\Compiler;
use Aegis\Token;

class BlockNode extends Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'block' ) ) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->traverseUp();
			$parser->parseAttribute();

			if( $parser->accept( Token::T_IDENT, 'prepend' ) || $parser->accept( Token::T_IDENT, 'append' ) ) {

				$parser->insert( new OptionNode( $parser->getCurrentToken()->getValue() ) );
				$parser->setAttribute();
				$parser->advance();
			}

			$parser->skip( Token::T_CLOSING_TAG );

			$parser->parseOutsideTag();

			$parser->skip( Token::T_OPENING_TAG );
			$parser->skip( Token::T_IDENT, '/block' );
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

		$blockHeadFunction = 'setBlock';

		if( $this->getAttribute( 1 ) ) {

			$optionAttr = $this->getAttribute( 1 );

			if( $optionAttr->getValue() === 'prepend' ) {

				$blockHeadFunction = 'prependBlock';

			} else if( $optionAttr->getValue() === 'append' ) {

				$blockHeadFunction = 'appendBlock';
			}
		}

		$compiler->head( '<?php $this->' . $blockHeadFunction . '( ' );
		$compiler->head( $name );
		$compiler->head( ', function() { ?>' );

		foreach( $this->getChildren() as $c ) {

			$subcompiler = new Compiler( $c );
			$compiler->head( $subcompiler->compile() );
		}

		$compiler->head( '<?php } ); ?>' );

		$compiler->write( '<?php $this->getBlock( ' );
		$compiler->write( $name );
		$compiler->write( '); ?>' );
	}
}