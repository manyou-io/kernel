<?php

declare(strict_types=1);

namespace AlpineGreen\Kernel\Config;

use AlpineGreen\Kernel\Contract\Config\LoaderFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\GlobFileLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class LoaderFactory implements LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory): DelegatingLoader
    {
        $fileLocator = new FileLocator([$currentWorkingDirectory]);

        $loaders = [
            new GlobFileLoader($fileLocator),
            new PhpFileLoader($containerBuilder, $fileLocator),
        ];

        $loaderResolver = new LoaderResolver($loaders);

        return new DelegatingLoader($loaderResolver);
    }
}
