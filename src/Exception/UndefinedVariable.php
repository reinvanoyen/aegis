<?php

namespace Aegis\Exception;

/**
 * Class UndefinedVariable
 * @package Aegis\Runtime
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class UndefinedVariable extends AegisError
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
