<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class Maintenance extends Command {

    protected function configure()
    {
      $this
          // the name of the command (the part after "bin/console")
          ->setName('maintenance')

          // the short description shown while running "php bin/console list"
          ->setDescription('Maintenance on git')

          // the full command description shown when running the command with
          // the "--help" option
          ->setHelp("Maintenance on git")
      ;

      $this->addOption(
          'action',
          'a',
          InputOption::VALUE_REQUIRED,
          'Action: deleteAll'
      );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $repo = $this->factory->repo();

      $action = $input->getOption('action');
      if($action=='deleteAll') {
        $repo->deleteAll();
        $output->writeLn("Ran delete all");
      }
    }

}
