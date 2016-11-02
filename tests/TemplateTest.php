<?php

use \Aegis\Template;

class TemplateTest extends PHPUnit_Framework_TestCase
{
	public function testVariable()
	{
		Template::$cacheDirectory = 'tests/cache/';
		Template::$templateDirectory = 'tests/templates/';

		$tpl = new Template();
		$tpl->test = 'My variable';
		$this->assertEquals('My variable', $tpl->render('variable-test') );
	}

	public function testString()
	{
		Template::$cacheDirectory = 'tests/cache/';
		Template::$templateDirectory = 'tests/templates/';

		$tpl = new Template();
		$this->assertEquals('My custom string', $tpl->render('string-test') );
	}

	public function testIf()
	{
		Template::$cacheDirectory = 'tests/cache/';
		Template::$templateDirectory = 'tests/templates/';

		$tpl = new Template();
		$tpl->condition = true;
		$this->assertEquals('Condition is true', $tpl->render('if-test') );
	}
}