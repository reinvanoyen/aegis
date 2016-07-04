<?php

use Aegis\Compiler;
use Aegis\Token;

class TestNode extends \Aegis\Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'test' ) ) {

			$parser->insert( new static() );
			$parser->advance();

			$parser->skip( Token::T_CLOSING_TAG );
			$parser->parseOutsideTag();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '<?php echo "this is a custom test node"; ?>' );
	}
}