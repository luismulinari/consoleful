<?php
/**
 * This file is part of LuisMulinari\Consolefull
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace LuisMulinari\Consolefull;

use Lcobucci\DependencyInjection\Builders\DelegatingBuilder;
use Lcobucci\DependencyInjection\ContainerBuilder;
use Lcobucci\DependencyInjection\ContainerConfig;
use Lcobucci\DependencyInjection\ContainerInjector;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * This class extends Symfony Console Application and build a dependency injection container
 *
 * @author Luis Henrique Mulinari <luis.mulinari@gmail.com>
 */
class Application extends SymfonyConsoleApplication implements ContainerAwareInterface
{
    use ContainerInjector;

    /**
     * @var ContainerBuilder
     */
    private $builder;

    /**
     * @var ContainerConfig
     */
    private $containerConfig;

    /**
     * Class constructor
     *
     * @param string $name
     * @param string $version
     * @param ContainerConfig $containerConfig
     * @param ContainerBuilder $builder
     */
    public function __construct(
        $name = 'UNKNOWN',
        $version = 'UNKNOWN',
        ContainerConfig $containerConfig = null,
        ContainerBuilder $builder = null
    ) {
        parent::__construct($name, $version);

        $this->containerConfig = $containerConfig;
        $this->builder = $builder ?: new DelegatingBuilder();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->injectContainer();

        parent::doRun($input, $output);
    }

    private function injectContainer()
    {
        if ($this->container === null && $this->containerConfig === null) {
            return;
        }

        if ($this->container === null) {
            $this->setContainer($this->builder->getContainer($this->containerConfig));
        }

        foreach ($this->all() as $command) {
            if ($command instanceof ContainerAwareInterface) {
                $command->setContainer($this->container);
            }
        }
    }
}
