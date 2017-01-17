<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class Admin extends MyCommand {

    protected function configure()
    {
      $this
          // the name of the command (the part after "bin/console")
          ->setName('admin')

          // the short description shown while running "php bin/console list"
          ->setDescription('Administrative commands')

          // the full command description shown when running the command with
          // the "--help" option
          ->setHelp("Administrative commands")
          ->addArgument(
              'action',
              InputArgument::REQUIRED,
              'action: git:deleteAll for delete git repo'
            )
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $repo = $this->factory->repo();

      $action = $input->getArgument('action');
      if($action=='git:deleteAll') {
        $repo->deleteAll();
        $output->writeLn("Ran delete all");
      }
    }

}
