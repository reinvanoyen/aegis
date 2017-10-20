<?php

use \Aegis\Template;

/**
 * Class AssignmentTest
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class AssignmentTest extends PHPUnit_Framework_TestCase
{
	public function testAssignment()
	{
		Template::$templateDirectory = 'tests/templates/';

		$tpl = new Template(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
		$tpl->setLexer(new \Aegis\Lexer());
		$tpl->setParser(new \Aegis\Parser());
		$tpl->setCompiler(new \Aegis\Compiler());

		$expectedResult = 'Assignment test works';

		$this->assertEquals($expectedResult, $tpl->render('assignment-test'));
	}
}
