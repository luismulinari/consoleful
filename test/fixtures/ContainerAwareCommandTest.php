<?php
namespace LuisMulinari\Consolefull\fixtures;

use Lcobucci\DependencyInjection\ContainerInjector;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class ContainerAwareCommandTest
 *
 * @author Luis Henrique Mulinari <luis.mulinari@gmail.com>
 */
class ContainerAwareCommandTest extends Command implements ContainerAwareInterface
{
    use ContainerInjector;

    protected function configure()
    {
        $this->setName('command:test2')->setDescription('description2');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('command2');
    }
}
