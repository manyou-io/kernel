<?php

declare(strict_types=1);

namespace AlpineGreen\Kernel;

use AlpineGreen\Kernel\Config\LoaderFactory;
use AlpineGreen\Kernel\Contract\LightKernel;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

abstract class BaseKernel implements LightKernel
{
    private Container|null $container = null;

    /**
     * @param string[]                $configFiles
     * @param CompilerPassInterface[] $compilerPasses
     * @param ExtensionInterface[]    $extensions
     */
    public function create(array $configFiles, array $compilerPasses = [], array $extensions = []): ContainerInterface
    {
        $containerBuilderFactory = new ContainerBuilderFactory(new LoaderFactory());

        $containerBuilder = $containerBuilderFactory->create($configFiles, $compilerPasses, $extensions);
        $containerBuilder->compile();

        $this->container = $containerBuilder;

        return $containerBuilder;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
