<?php

use \Aegis\Template;

/**
 * Class DefaultRuntimeTest
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class DefaultRuntimeTest extends PHPUnit_Framework_TestCase
{
    public function testElse()
    {
        Template::$templateDirectory = 'tests/templates/';

        $expectedResult = '1234';

        $tpl = new Template(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
        $tpl->setLexer(new \Aegis\Lexer());
        $tpl->setParser(new \Aegis\Parser());
        $tpl->setCompiler(new \Aegis\Compiler());
        $tpl->condition = false;
        $this->assertEquals($expectedResult, $tpl->render('else-test'));
    }

    public function testElseIf()
    {
        Template::$templateDirectory = 'tests/templates/';

        $expectedResult = '123';

        $tpl = new Template(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
        $tpl->setLexer(new \Aegis\Lexer());
        $tpl->setParser(new \Aegis\Parser());
        $tpl->setCompiler(new \Aegis\Compiler());
        $tpl->condition = false;
        $this->assertEquals($expectedResult, $tpl->render('elseif-test'));
    }
}
