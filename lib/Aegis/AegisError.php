<?php

namespace Aegis;

/**
 * Class AegisError
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class AegisError extends \Exception
{
	/**
	 * @var string
	 */
	protected $sourceCode;

	/**
	 * @return void
	 */
	public function printExceptionDetail() : void
	{
		echo $this->getMessage();
	}

	/**
	 * @param string $source
	 * @return void
	 */
	public function setSourceCode(string $source) : void
	{
		$this->sourceCode = $source;
	}
}
