<?php

declare(strict_types=1);

namespace Config\Loaders;

interface LoaderInterface
{
    public function parse(): array;
}