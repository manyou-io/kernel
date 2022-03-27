<?php

declare(strict_types=1);

namespace AlpineGreen\Kernel\Contract;

use Psr\Container\ContainerInterface;

interface LightKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles): ContainerInterface;

    public function getContainer(): ContainerInterface;
}
