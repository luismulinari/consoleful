<?php
/**
 * This file is part of LuisMulinari\Consoleful
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace LuisMulinari\Consoleful\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ContainerAwareCommandTest
 *
 * @author Luis Henrique Mulinari <luis.mulinari@gmail.com>
 */
class ContainerAwareCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \LogicException
     * @expectedExceptionMessage The container cannot be retrieved
     */
    public function getContainerMustRaiseExceptionWhenContainerNotConfigured()
    {
        $command = $this->getMockForAbstractClass(ContainerAwareCommand::class, ['commandName']);

        $getContainerReflectionMethod = new \ReflectionMethod($command, 'getContainer');
        $getContainerReflectionMethod->setAccessible(true);
        $getContainerReflectionMethod->invoke($command);
    }

    /**
     * @test
     */
    public function setContainerShouldConfigureContainer()
    {
        $command = $this->getMockForAbstractClass(ContainerAwareCommand::class, ['commandName']);

        $container = $this->getMock(ContainerInterface::class);

        $command->setContainer($container);

        $getContainerReflectionMethod = new \ReflectionMethod($command, 'getContainer');
        $getContainerReflectionMethod->setAccessible(true);

        $this->assertSame($container, $getContainerReflectionMethod->invoke($command));
    }
}
