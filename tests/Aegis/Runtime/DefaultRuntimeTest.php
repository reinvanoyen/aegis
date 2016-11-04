<?php

use \Aegis\Template;

class DefaultRuntimeTest extends PHPUnit_Framework_TestCase
{
    public function testBasics()
    {
        Template::$cacheDirectory = 'tests/cache/';
        Template::$templateDirectory = 'tests/templates/';

        $string = 'UOspIkq124MLd,shdfDRDFGbnjsjs,k,oao';
        $number = 12;

        $tpl = new Template();
        $tpl->string = $string;
        $tpl->number = $number;

        $expectedResult = $string.' '.$number.' something';

        $this->assertEquals($expectedResult, $tpl->render('basics-test'));
    }

    public function testRaw()
    {
        Template::$cacheDirectory = 'tests/cache/';
        Template::$templateDirectory = 'tests/templates/';

        $html = '<span data-name="test">test</span>';

        $tpl = new Template();
        $tpl->html = $html;

        $expectedResult = '<div>test</div><span data-name="test">test</span>';

        $this->assertEquals($expectedResult, $tpl->render('raw-test'));
    }

    public function testIf()
    {
        Template::$cacheDirectory = 'tests/cache/';
        Template::$templateDirectory = 'tests/templates/';

        $expectedResult = '123456789';

        $tpl = new Template();
        $tpl->condition = true;
        $this->assertEquals($expectedResult, $tpl->render('if-test'));
    }

    public function testElse()
    {
        Template::$cacheDirectory = 'tests/cache/';
        Template::$templateDirectory = 'tests/templates/';

        $expectedResult = '1234';

        $tpl = new Template();
        $tpl->condition = false;
        $this->assertEquals($expectedResult, $tpl->render('else-test'));
    }

    public function testElseIf()
    {
        Template::$cacheDirectory = 'tests/cache/';
        Template::$templateDirectory = 'tests/templates/';

        $expectedResult = '123';

        $tpl = new Template();
        $tpl->condition = false;
        $this->assertEquals($expectedResult, $tpl->render('elseif-test'));
    }
}
