<?php

namespace LuisMulinari\Consolefull\test\fixtures;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CommandTest
 *
 * @author Luis Henrique Mulinari <luis.mulinari@gmail.com>
 */
class CommandTest extends Command
{
    protected function configure()
    {
        $this->setName('command:test')->setDescription('description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('command');
    }
}