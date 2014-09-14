<?php

namespace LuisMulinari\Consolefull\test\fixtures;

use LuisMulinari\Consolefull\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ContainerAwareCommandTest
 *
 * @author Luis Henrique Mulinari <luis.mulinari@gmail.com>
 */
class ContainerAwareCommandTest extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('command:test2')->setDescription('description2');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('command2');
    }
}
