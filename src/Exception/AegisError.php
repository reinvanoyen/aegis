<?php

namespace Aegis\Exception;

/**
 * Class AegisError
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class AegisError extends \Exception
{
    public function getExceptionType(): string
    {
        return static::class;
    }

    public function getExceptionMessage(): string
    {
        return $this->getMessage();
    }

    /**
     * @return string
     */
    public function getExceptionDetail(): string
    {
        return '';
    }
}
