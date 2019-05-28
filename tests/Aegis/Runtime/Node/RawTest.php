<?php

use \Aegis\Template;

/**
 * Class RawTest
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class RawTest extends PHPUnit_Framework_TestCase
{
    public function testRaw()
    {
        Template::$templateDirectory = 'tests/templates/';

        $html = '<span data-name="test">test</span>';

        $tpl = new Template(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
        $tpl->setLexer(new \Aegis\Lexer());
        $tpl->setParser(new \Aegis\Parser());
        $tpl->setCompiler(new \Aegis\Compiler());
        $tpl->html = $html;

        $expectedResult = '<div>test</div><span data-name="test">test</span>';

        $this->assertEquals($expectedResult, $tpl->render('raw-test'));
    }
}
