<?php

namespace Aegis\Error;

use Aegis\AegisError;

/**
 * Class SyntaxError
 * @package Aegis\Error
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class SyntaxError extends AegisError
{
    /**
     * @var int
     */
    protected $line;

    /**
     * @var int
     */
    private $position;

    public function __construct(string $message, int $srcLine = 0, int $srcPosition = 0)
    {
        parent::__construct('Syntax error on line ' . $srcLine . ': ' . $message);

        $this->line = $srcLine;
        $this->position = $srcPosition;
    }

    /**
     * @return void
     */
    public function printExceptionDetail() : void
    {
        echo '<h4>' . $this->getMessage() . '</h4>';
        echo '<h3>Position ' . $this->position . '</h3>';

        $lines = explode("\n", $this->sourceCode);

        $start = max(0, $this->line - 10);
        $end = min(count($lines), $this->line + 10);

        echo '<pre>';

        for ($i = $start; $i < $end; $i++) {
            if ($i === $this->line - 1) {
                $beforeError = substr($lines[$i], 0, max(0, $this->position - 1));
                $error = substr($lines[$i], max(0, $this->position - 1), 1);
                if (! $error) {
                    $error = ' ';
                }
                $afterError = substr($lines[$i], $this->position);

                echo '<span style="display: block; padding: 2px 0; border-bottom: 1px dotted #777; background-color: #e0e0e0; color: #444;">';
                echo '<strong>' . ($i + 1) . '</strong> ';
                echo htmlspecialchars($beforeError);
                echo '<span style="background-color: red; color: white;">' . htmlspecialchars($error) . '</span>';
                echo htmlspecialchars($afterError);
                echo '</span>';
            } else {
                echo '<span style="display: block; padding: 2px 0; border-bottom: 1px solid #ccc;">';
                echo '<strong>' . ($i + 1) . '</strong> ';
                echo htmlspecialchars($lines[$i]);
                echo '</span>';
            }
        }

        echo '</pre>';
    }
}
