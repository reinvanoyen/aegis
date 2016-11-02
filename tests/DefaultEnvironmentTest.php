<?php

use \Aegis\Template;

class DefaultEnvironmentTest extends PHPUnit_Framework_TestCase
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

		$this->assertEquals($expectedResult, $tpl->render('basics-test') );
	}

	public function testraw()
	{
		Template::$cacheDirectory = 'tests/cache/';
		Template::$templateDirectory = 'tests/templates/';

		$html = '<span data-name="test">test</span>';

		$tpl = new Template();
		$tpl->html = $html;

		$expectedResult = '<div>test</div><span data-name="test">test</span>';

		$this->assertEquals($expectedResult, $tpl->render('raw-test') );
	}

	public function testIf()
	{
		Template::$cacheDirectory = 'tests/cache/';
		Template::$templateDirectory = 'tests/templates/';

		$tpl = new Template();
		$tpl->condition = true;

		$expectedResult = 'test 1test 2test 3test 4test 5test 6test 7test 8';

		$this->assertEquals($expectedResult, $tpl->render('if-test') );
	}
}