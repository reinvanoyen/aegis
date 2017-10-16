<?php

namespace Aegis;

/**
 * Class NoTokenAtIndex
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class NoTokenAtIndex extends AegisError
{
    /**
     * NoTokenAtIndex constructor.
     * @param string $index
     */
    public function __construct($index)
    {
        parent::__construct('No token found in the TokenStream at index '.$index);
    }
}
