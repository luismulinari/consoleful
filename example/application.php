<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Lcobucci\DependencyInjection\ContainerConfig;
use LuisMulinari\Consoleful\Command\ContainerAwareCommand;
use LuisMulinari\Consoleful\Application;
use Lcobucci\DependencyInjection\ContainerInjector;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class ExampleCommand extends ContainerAwareCommand
{
    use ContainerInjector;

    protected function configure()
    {
        $this->setName("example");
        $this->setDescription('* Simple example');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $progress = new ProgressBar($output, 5);

        $progress->start();

        $i = 0;
        while ($i++ < 5) {
            $progress->advance();

            sleep(1);
        }

        $progress->finish();

        $output->writeln($container->getParameter('parameter.example'));
    }
}

class DatabaseCommand extends ContainerAwareCommand
{
    use ContainerInjector;

    protected function configure()
    {
        $this->setName('database');
        $this->setDescription('* Exemple with database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!class_exists('Doctrine\DBAL\Connection')) {
            $output->write('You need to include "doctrine/dbal" package in your composer.json');

            return false;
        }

        $databaseTables = $this->getContainer()->get('database.connection')->query('SHOW TABLES')->fetchAll();

        $tableHelper = $this->getHelper('table');

        $tableHelper->setHeaders(['Table name']);
        $tableHelper->setRows($databaseTables);

        $tableHelper->render($output);
    }
}


$application = new Application(
    'Application name',
    'Version',
    new ContainerConfig(__DIR__ . '/services.xml') // services.[xml|yml|php]
);

$application->add(new ExampleCommand());
$application->add(new DatabaseCommand());

$application->run();
