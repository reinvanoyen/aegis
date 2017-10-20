<?php

use \Aegis\Template;

/**
 * Class ForTest
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ForTest extends PHPUnit_Framework_TestCase
{
	public function testFor()
	{
		Template::$templateDirectory = 'tests/templates/';

		$tpl = new Template(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
		$tpl->setLexer(new \Aegis\Lexer());
		$tpl->setParser(new \Aegis\Parser());
		$tpl->setCompiler(new \Aegis\Compiler());

		$expectedResult = 'ReinVanOyen';

		$this->assertEquals($expectedResult, $tpl->render('for-test'));
	}
}
