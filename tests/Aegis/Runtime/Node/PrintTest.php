<?php

use \Aegis\Template;

/**
 * Class PrintTest
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class PrintTest extends PHPUnit_Framework_TestCase
{
    public function testPrint()
    {
        Template::$templateDirectory = 'tests/templates/';

        $string = 'UOspIkq124MLd,shdfDRDFGbnjsjs,k,oao';
        $number = 12;

        $tpl = new Template(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
        $tpl->setLexer(new \Aegis\Lexer());
        $tpl->setParser(new \Aegis\Parser());
        $tpl->setCompiler(new \Aegis\Compiler());
        $tpl->string = $string;
        $tpl->number = $number;

        $expectedResult = $string.' '.$number.' something';

        $this->assertEquals($expectedResult, $tpl->render('print-test'));
    }
}
