<?php
/**
 * This file is part of LuisMulinari\Consoleful
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace LuisMulinari\Consoleful;

use Lcobucci\DependencyInjection\ContainerBuilder;
use Lcobucci\DependencyInjection\ContainerConfig;
use Lcobucci\DependencyInjection\Builders\DelegatingBuilder;
use LuisMulinari\Consoleful\fixtures\CommandTest;
use LuisMulinari\Consoleful\fixtures\ContainerAwareCommandTest;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ApplicationTest
 *
 * @author Luis Henrique Mulinari <luis.mulinari@gmail.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $builder;

    /**
     * @var ContainerConfig
     */
    protected $config;

    protected function setUp()
    {
        $this->config = $this->getMock(ContainerConfig::class, [], [], '', false);
        $this->builder = $this->getMockForAbstractClass(ContainerBuilder::class, [], '', true, true, true, ['getContainer']);
    }

    /**
     * @test
     *
     * @covers LuisMulinari\Consoleful\Application::__construct
     * @covers Symfony\Component\Console\Application::__construct
     */
    public function constructShouldConfigureTheAttributes()
    {
        $application = new Application('name', 'version', $this->config, $this->builder);

        $this->assertAttributeEquals('name', 'name', $application);
        $this->assertAttributeEquals('version', 'version', $application);
        $this->assertAttributeSame($this->config, 'containerConfig', $application);
        $this->assertAttributeSame($this->builder, 'builder', $application);
    }

    /**
     * @test
     *
     * @covers LuisMulinari\Consoleful\Application::__construct
     * @covers Symfony\Component\Console\Application::__construct
     */
    public function constructShouldCreateBuilderWhenNotInformed()
    {
        $application = new Application('name', 'version', $this->config);

        $this->assertAttributeInstanceOf(DelegatingBuilder::class, 'builder', $application);
    }

    /**
     * @test
     *
     * @covers LuisMulinari\Consoleful\Application::__construct
     * @covers LuisMulinari\Consoleful\Application::doRun
     * @covers LuisMulinari\Consoleful\Application::injectContainer
     * @covers Symfony\Component\Console\Application::__construct
     * @covers Symfony\Component\Console\Application::doRun
     * @covers Lcobucci\DependencyInjection\ContainerInjector::setContainer
     * @covers Lcobucci\DependencyInjection\ContainerInjector::getContainer
     */
    public function doRunShouldNotBuildTheContainerWhenNoConfigurationWasUsed()
    {
        $input = $this->getMock(InputInterface::class);
        $output = $this->getMock(OutputInterface::class);

        $this->builder->expects($this->never())
                      ->method('getContainer');

        $application = new Application('name', 'version', null, $this->builder);
        $application->doRun($input, $output);

        $this->assertAttributeEquals(null, 'container', $application);
    }

    /**
     * @test
     *
     * @covers LuisMulinari\Consoleful\Application::__construct
     * @covers LuisMulinari\Consoleful\Application::doRun
     * @covers LuisMulinari\Consoleful\Application::injectContainer
     * @covers Symfony\Component\Console\Application::__construct
     * @covers Symfony\Component\Console\Application::doRun
     * @covers Lcobucci\DependencyInjection\ContainerInjector::setContainer
     * @covers Lcobucci\DependencyInjection\ContainerInjector::getContainer
     */
    public function doRunShouldBuildTheContainerIfItWasnConfiguredYet()
    {
        $input = $this->getMock(InputInterface::class);
        $output = $this->getMock(OutputInterface::class);
        $container = $this->getMock(ContainerInterface::class);

        $this->builder->expects($this->once())
                      ->method('getContainer')
                      ->with($this->config)
                      ->willReturn($container);

        $application = new Application('name', 'version', $this->config, $this->builder);
        $application->doRun($input, $output);

        $this->assertAttributeSame($container, 'container', $application);
    }

    /**
     * @test
     *
     * @covers LuisMulinari\Consoleful\Application::__construct
     * @covers LuisMulinari\Consoleful\Application::doRun
     * @covers LuisMulinari\Consoleful\Application::injectContainer
     * @covers Symfony\Component\Console\Application::__construct
     * @covers Symfony\Component\Console\Application::add
     * @covers Symfony\Component\Console\Application::doRun
     * @covers Lcobucci\DependencyInjection\ContainerInjector::setContainer
     * @covers Lcobucci\DependencyInjection\ContainerInjector::getContainer
     */
    public function doRunShouldInjectTheContainerOnContainerAwareCommands()
    {
        $input = $this->getMock(InputInterface::class);
        $output = $this->getMock(OutputInterface::class);
        $container = $this->getMock(ContainerInterface::class);

        $this->builder->expects($this->never())
                      ->method('getContainer');

        $command = new CommandTest();
        $command2 = new ContainerAwareCommandTest();

        $application = new Application('name', 'version', $this->config, $this->builder);
        $application->add($command);
        $application->add($command2);
        $application->setContainer($container);
        $application->doRun($input, $output);

        $this->assertAttributeSame($container, 'container', $command2);
    }
}
