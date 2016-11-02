<?php

namespace Aegis\Runtime;

class UndefinedVariable extends \Exception
{
	public function __construct($name)
	{
		parent::__construct('Undefined runtime variable: '.$name);
	}
}
