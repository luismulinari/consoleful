<?php
/**
 * This file is part of LuisMulinari\Consoleful
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace LuisMulinari\Consoleful;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This class extends Symfony Console Application and build a dependency injection container
 *
 * @author Luis Henrique Mulinari <luis.mulinari@gmail.com>
 */
class Application extends SymfonyConsoleApplication
{
    /**
     * Symfony dependency injection container
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Class constructor
     *
     * @param string $name
     * @param string $version
     * @param mixed $resource
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN', $resource)
    {
        parent::__construct($name, $version);

        $this->buildContainer($resource);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->all() as $command) {
            if ($command instanceof ContainerAwareInterface) {
                $command->setContainer($this->container);
            }
        }

        parent::doRun($input, $output);
    }

    /**
     * Build a dependency injection container
     *
     * @param mixed $resource
     */
    protected function buildContainer($resource)
    {
        if ($this->container === null) {
            $this->container = new ContainerBuilder();

            $fileLocator = new FileLocator(dirname($resource));

            $loaderResolver = new LoaderResolver(
                [
                    new XmlFileLoader($this->container, $fileLocator),
                    new YamlFileLoader($this->container, $fileLocator),
                    new PhpFileLoader($this->container, $fileLocator)
                ]
            );
            $loader = new DelegatingLoader($loaderResolver);
            $loader->load(basename($resource));
        }
    }
}
