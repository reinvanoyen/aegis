<?php

namespace Aegis\Contracts;

interface FilesystemInterface
{
    public function exists(string $path): bool;
    public function isWriteable(string $path): bool;
    public function isReadable(string $path): bool;
    public function modificationTime(string $path): int;
    public function get(string $path);
    public function put(string $path, $contents);
    public function delete(string $path);
}
