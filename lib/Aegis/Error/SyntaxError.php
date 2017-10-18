<?php

namespace Aegis\Error;

use Aegis\AegisError;
use Aegis\Token;

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
	 * @var Token
	 */
    private $token;

    public function __construct(string $message, int $srcLine = 0, Token $token)
    {
        parent::__construct('Syntax error on line ' . $srcLine . ': ' . $message);

        $this->line = $srcLine;
        $this->token = $token;
    }

    /**
     * @return void
     */
    public function printExceptionDetail() : void
    {
    	echo '<h3>SyntaxError</h3>';
        echo '<h4>' . $this->getMessage() . '</h4>';

        $lines = explode("\n", $this->sourceCode);

        $start = max(0, $this->line - 10);
        $end = min(count($lines), $this->line + 10);

        echo '<pre style="background-color: #333; color: #d9d9d9; padding: 5px;">';

        for ($i = $start; $i < $end; $i++) {

            if ($i === $this->line - 1) {

                $beforeError = substr($lines[$i], 0, $this->token->getStartPosition());
                $error = substr($lines[$i], $this->token->getStartPosition(), $this->token->getEndPosition() - $this->token->getStartPosition());
                $afterError = substr($lines[$i], $this->token->getEndPosition());

                echo '<span style="display: block; padding: 3px 0; background-color: #222; color: #777;">';
	            echo '<strong style="color: #777; padding: 0 5px; display: inline-block; width: 30px; text-align: right;">' . ($i + 1) . '</strong> ';
                echo htmlspecialchars($beforeError);
                echo '<span style="background-color: red; color: white; padding: 0 2px;">' . htmlspecialchars($error) . '</span>';
                echo htmlspecialchars($afterError);
                echo '</span>';
            } else {
                echo '<span style="display: block; padding: 3px 0; border-bottom: 1px dotted #222;">';
                echo '<strong style="color: #777; padding: 0 5px; display: inline-block; width: 30px; text-align: right;">' . ($i + 1) . '</strong> ';
                echo htmlspecialchars($lines[$i]);
                echo '</span>';
            }
        }

        echo '</pre>';
    }
}
