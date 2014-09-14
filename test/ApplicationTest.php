<?php
/**
 * This file is part of LuisMulinari\Consoleful
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace LuisMulinari\Consoleful;

use LuisMulinari\Consoleful\test\fixtures\ContainerAwareCommandTest;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Class ApplicationTest
 *
 * @author Luis Henrique Mulinari <luis.mulinari@gmail.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var mixed
     */
    protected $resource;

    public function setUp()
    {
        $this->resource = __DIR__ . '/fixtures' . DIRECTORY_SEPARATOR . 'services.xml';
    }

    /**
     * @test
     */
    public function constructMustCallParentConstructor()
    {
        $application = new Application('name', 'version', $this->resource);

        $this->assertSame('name', $application->getName());
        $this->assertSame('version', $application->getVersion());
    }

    /**
     * @test
     */
    public function constructMustInitializeContainer()
    {
        $application = new Application(null, null, $this->resource);

        $class = new \ReflectionClass('LuisMulinari\Consoleful\Application');
        $property = $class->getProperty('container');
        $property->setAccessible(true);

        $this->assertInstanceOf(
            'Symfony\Component\DependencyInjection\ContainerBuilder',
            $property->getValue($application)
        );
    }

    /**
     * @test
     */
    public function doRunMustSetContainerInContainerAwareInterfaceCommands()
    {
        $application = new Application(null, null, $this->resource);

        $class = new \ReflectionClass($application);
        $property = $class->getProperty('container');
        $property->setAccessible(true);
        $container = $property->getValue($application);

        $command2 = new ContainerAwareCommandTest();

        $application->add($command2);

        $input = new ArgvInput(['command:test']);
        $output = new NullOutput();
        $application->doRun($input, $output);

        $getContainerReflectionMethod = new \ReflectionMethod(
            $command2, 'getContainer'
        );
        $getContainerReflectionMethod->setAccessible(true);

        $this->assertSame($container, $getContainerReflectionMethod->invoke($command2));
    }

    /**
     * @test
     */
    public function buildContainerShouldNotCreateNewContainerIfContainerExists()
    {
        $application = new Application(null, null, $this->resource);

        $class = new \ReflectionClass($application);
        $property = $class->getProperty('container');
        $property->setAccessible(true);

        $container = $property->getValue($application);

        $buildContainerReflectionMethod = new \ReflectionMethod($application, 'buildContainer');
        $buildContainerReflectionMethod->setAccessible(true);
        $buildContainerReflectionMethod->invoke($application, $this->resource);

        $this->assertSame($container, $property->getValue($application));
    }
}
