<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;

class ForNode extends \Aegis\Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'for' ) ) {

			$parser->insert( new static() );
			$parser->advance();
			$parser->traverseUp();

			if( $parser->accept( Token::T_VAR ) ) {

				// T_VAR as first attribute

				$parser->insert( new VariableNode( $parser->getCurrentToken()->getValue() ) );
				$parser->setAttribute();
				$parser->advance();

				$parser->expect( Token::T_IDENT, 'in' );
				$parser->advance();

				ExpressionNode::parse( $parser );
				$parser->setAttribute();

			} else if( $parser->accept( Token::T_NUMBER ) ) {

				// T_NUMBER as first attribute

				$parser->insert( new NumberNode( $parser->getCurrentToken()->getValue() ) );
				$parser->setAttribute();
				$parser->advance();

				$parser->expect( Token::T_IDENT, 'to' );
				$parser->advance();

				if( $parser->expect( Token::T_NUMBER ) ) {

					$parser->insert( new NumberNode( $parser->getCurrentToken()->getValue() ) );
					$parser->setAttribute();
					$parser->advance();
				}
			}

			$parser->skip( Token::T_CLOSING_TAG );

			$parser->parseOutsideTag();

			$parser->skip( Token::T_OPENING_TAG );
			$parser->skip( Token::T_IDENT, '/for' );
			$parser->skip( Token::T_CLOSING_TAG );

			$parser->traverseDown();
			$parser->parseOutsideTag();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( $compiler )
	{
		$loopitem = $this->getAttribute( 0 );
		$arrayable = $this->getAttribute( 1 );

		if( $loopitem instanceof VariableNode ) {

			$compiler->write( '<?php foreach(' );
			$arrayable->compile( $compiler );
			$compiler->write( ' as ' );
			$loopitem->compile( $compiler );
			$compiler->write( '): ?>' );

			foreach( $this->getChildren() as $c ) {

				$c->compile( $compiler );
			}

			$compiler->write( '<?php endforeach; ?>' );

		} else if( $loopitem instanceof NumberNode ) {

			$compiler->write( '<?php for( $i = ' );
			$loopitem->compile( $compiler );
			$compiler->write( '; $i <= ' );
			$arrayable->compile( $compiler );
			$compiler->write( '; $i++ ): ?>' );

			foreach( $this->getChildren() as $c ) {

				$c->compile( $compiler );
			}

			$compiler->write( '<?php endfor; ?>' );
		}
	}
}