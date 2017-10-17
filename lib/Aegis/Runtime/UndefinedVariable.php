<?php

namespace Aegis\Runtime;

/**
 * Class UndefinedVariable
 * @package Aegis\Runtime
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class UndefinedVariable extends \Exception
{
    /**
     * UndefinedVariable constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct('Undefined runtime variable: '.$name);
    }
}
