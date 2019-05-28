<?php

namespace Aegis;

use Aegis\Contracts\ConfigInterface;
use Aegis\Contracts\EngineInterface;
use Aegis\Contracts\FilesystemInterface;
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
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var FilesystemInterface $filesystem
     */
    private $filesystem;

    /**
     * @var RuntimeInterface
     */
    private $runtime;

    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * Template constructor.
     * @param ConfigInterface $config
     * @param EngineInterface $engine
     * @param RuntimeInterface $runtime
     */
    public function __construct(ConfigInterface $config, FilesystemInterface $filesystem, EngineInterface $engine, RuntimeInterface $runtime)
    {
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->engine = $engine;
        $this->runtime = $runtime;

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
     * @param string $tplName
     * @param string $part
     * @return string
     */
    public function render(string $tplName, string $part = null): string
    {
        $filename = $this->config->get('directory').'/'.$tplName.'.'.$this->config->get('extension');
        $cacheFilename = $this->config->get('cache_directory').'/'.($part ? $part.'/' : '').md5($tplName).'.'.$this->config->get('extension').'.'.$this->config->get('cache_extension');

        if (!$this->filesystem->exists($filename)) {
            throw new AegisError('File not found '.$filename);
        }

        if (
            !$this->filesystem->exists($cacheFilename) ||
            $this->filesystem->modificationTime($filename) >= $this->filesystem->modificationTime($cacheFilename) ||
            $this->config->get('debug')
        ) {
            $result = $this->engine->evaluate($this->filesystem->get($filename));

            switch ($part) {
                case 'head':
                    $result = $this->engine->getCompiler()->getHead();
                    break;
                case 'body':
                    $result = $this->engine->getCompiler()->getBody();
                    break;
            }

            $this->filesystem->put($cacheFilename, $result);
        }

        return $this->execute($cacheFilename);
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
            require_once $this->config->get('exception_view');
        } else {
            throw $exception;
        }
    }
}
