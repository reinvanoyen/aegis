<?php

use \Aegis\Template;

/**
 * Class PhpTest
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class PhpTest extends PHPUnit_Framework_TestCase
{
	public function testPhp()
	{
		Template::$templateDirectory = 'tests/templates/';

		$expectedResult = "ok nice";

		$tpl = new Template(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
		$tpl->setLexer(new \Aegis\Lexer());
		$tpl->setParser(new \Aegis\Parser());
		$tpl->setCompiler(new \Aegis\Compiler());
		$tpl->condition = 1;
		$this->assertEquals($expectedResult, $tpl->render('php-test'));
	}
}
