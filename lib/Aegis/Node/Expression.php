<?php

namespace Aegis\Node;

use Aegis\Token;

class Expression extends Node
{
	public static function parse( $parser )
	{
		if(
			$parser->accept( Token::T_VAR ) ||
			$parser->accept( Token::T_STRING ) ||
			$parser->accept( Token::T_NUMBER )
		)
		{
			if( ! $parser->getScope() instanceof Expression ) {

				$parser->wrap( new Expression() );
			}

			if( $parser->accept( Token::T_OP ) ) {

				self::parse( $parser );
			}

			if( $parser->skip( Token::T_CLOSING_TAG ) ) {

				if( $parser->getScope() instanceof Expression )
				{
					$parser->traverseDown();
				}

				$parser->parseOutsideTag();
			}
		}
	}

	public function compile( $compiler )
	{
		if( $this->isAttribute() )
		{
			foreach( $this->getChildren() as $c )
			{
				$c->compile( $compiler );
			}
		}
		else
		{
			$compiler->write( '<?php echo htmlspecialchars(' );

			foreach( $this->getChildren() as $c )
			{
				$c->compile( $compiler );
			}

			$compiler->write( '); ?>' );
		}
	}
}
