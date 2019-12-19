<?php

namespace Aegis\Exception;

/**
 * Class ParseError
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ParseError extends AegisError
{
    /**
     * @var int
     */
    protected $srcLine;

    /**
     * ParseError constructor.
     *
     * @param string $message
     * @param int $srcLine
     */
    public function __construct($message, $srcLine = 0)
    {
        parent::__construct($message.' on line '.$srcLine);

        $this->srcLine = $srcLine;
    }

    /**
     * @return int
     */
    public function getSrcLine()
    {
        return $this->srcLine;
    }
}
