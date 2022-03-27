<?php

declare(strict_types=1);

namespace AlpineGreen\Kernel;

use AlpineGreen\Kernel\Contract\Config\LoaderFactoryInterface;
use AlpineGreen\Kernel\DependencyInjection\LoadExtensionConfigsCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

use function getcwd;

final class ContainerBuilderFactory
{
    public function __construct(
        private LoaderFactoryInterface $loaderFactory
    ) {
    }

    /**
     * @param string[]                $configFiles
     * @param CompilerPassInterface[] $compilerPasses
     * @param ExtensionInterface[]    $extensions
     */
    public function create(array $configFiles, array $compilerPasses, array $extensions): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();

        $this->registerExtensions($containerBuilder, $extensions);
        $this->registerConfigFiles($containerBuilder, $configFiles);
        $this->registerCompilerPasses($containerBuilder, $compilerPasses);

        // this calls load() method in every extensions
        // ensure these extensions are implicitly loaded
        $compilerPassConfig = $containerBuilder->getCompilerPassConfig();
        $compilerPassConfig->setMergePass(new LoadExtensionConfigsCompilerPass());

        return $containerBuilder;
    }

    /**
     * @param ExtensionInterface[] $extensions
     */
    private function registerExtensions(ContainerBuilder $containerBuilder, array $extensions): void
    {
        foreach ($extensions as $extension) {
            $containerBuilder->registerExtension($extension);
        }
    }

    /**
     * @param CompilerPassInterface[] $compilerPasses
     */
    private function registerCompilerPasses(ContainerBuilder $containerBuilder, array $compilerPasses): void
    {
        foreach ($compilerPasses as $compilerPass) {
            $containerBuilder->addCompilerPass($compilerPass);
        }
    }

    /**
     * @param string[] $configFiles
     */
    private function registerConfigFiles(ContainerBuilder $containerBuilder, array $configFiles): void
    {
        $delegatingLoader = $this->loaderFactory->create($containerBuilder, getcwd());
        foreach ($configFiles as $configFile) {
            $delegatingLoader->load($configFile);
        }
    }
}
