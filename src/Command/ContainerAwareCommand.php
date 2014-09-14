<?php
/**
 * This file is part of LuisMulinari\Consolefull
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace LuisMulinari\Consolefull\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class ContainerAwareCommand
 *
 * @author Luis Henrique Mulinari <luis.mulinari@gmail.com>
 */
abstract class ContainerAwareCommand extends Command implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * @return ContainerInterface
     * @throws \LogicException
     */
    protected function getContainer()
    {
        if (null === $this->container) {
            throw new \LogicException('The container cannot be retrieved');
        }

        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
