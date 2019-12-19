<?php

namespace Aegis;

use Aegis\Contracts\EngineInterface;
use Aegis\Contracts\LoaderInterface;
use Aegis\Contracts\RuntimeInterface;
use Aegis\Exception\AegisError;

/**
 * Class Template
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class Template
{
    /**
     * @var LoaderInterface $loader
     */
    private $loader;

    /**
     * @var RuntimeInterface $runtime
     */
    private $runtime;

    /**
     * @var EngineInterface $engine
     */
    private $engine;

    /**
     * Template constructor.
     * @param LoaderInterface $loader
     * @param EngineInterface $engine
     * @param RuntimeInterface $runtime
     */
    public function __construct(LoaderInterface $loader, EngineInterface $engine, RuntimeInterface $runtime)
    {
        $this->loader = $loader;
        $this->engine = $engine;
        $this->runtime = $runtime;

        // Set a specialized exception handler
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $name => $value) {
                $this->set($name, $value);
            }
        } else {
            $this->runtime->set($key, $value);
        }
    }

    /**
     * @param string $templateName
     * @param string|null $part
     * @return string
     */
    public function render(string $templateName, string $part = null): string
    {
        $templateKey = ($part ? $part.'/'.$templateName : $templateName);

        // Check if we can fetch it from cache
        if ($this->loader->isCached($templateKey)) {

            // Execute the key we found in cache
            return $this->execute($this->loader->getCacheKey($templateKey));
        }

        // Load the requested source
        $source = $this->loader->get($templateName);

        // Evaluate the source
        $result = $this->engine->evaluate($source);

        // Get the right part
        switch ($part) {
            case 'head':
                $result = $this->engine->getCompiler()->getHead();
                break;
            case 'body':
                $result = $this->engine->getCompiler()->getBody();
                break;
        }

        // Write it to the cache
        $this->loader->setCache($templateKey, $result);

        // Execute from cache
        return $this->execute($this->loader->getCacheKey($templateKey));
    }

    /**
     * @param $filename
     * @return string
     */
    private function execute($filename)
    {
        $scopedRequire = function (string $filename, array $variables) {
            extract($variables);
            require $filename;
        };

        ob_start();

        $scopedRequire($filename, [
            'tpl' => $this,
            'env' => $this->runtime,
        ]);

        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }

    /**
     * @param $exception
     * @return void
     */
    public function handleException($exception): void
    {
        if ($exception instanceof AegisError) {
            require __DIR__.'/Resources/views/exception.html.php';
        } else {
            throw $exception;
        }
    }
}
