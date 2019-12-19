<?php

namespace Aegis\Contracts;

/**
 * Interface RuntimeInterface
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
interface RuntimeInterface
{
    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value);
}
