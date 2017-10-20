<?php

use \Aegis\Template;

/**
 * Class IfTest
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class IfTest extends PHPUnit_Framework_TestCase
{
	public function testIf()
	{
		Template::$templateDirectory = 'tests/templates/';

		$expectedResult = '12345678910';

		$tpl = new Template(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
		$tpl->setLexer(new \Aegis\Lexer());
		$tpl->setParser(new \Aegis\Parser());
		$tpl->setCompiler(new \Aegis\Compiler());
		$tpl->condition = true;
		$this->assertEquals($expectedResult, $tpl->render('if-test'));
	}
}
