<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// get diff and send result by email (this is parallel to the UI)
class PostCommit extends Command {

    protected function configure()
    {
      $this
          // the name of the command (the part after "bin/console")
          ->setName('post-commit')

          // the short description shown while running "php bin/console list"
          ->setDescription('Email a user with the diff of a table between two dates')

          // the full command description shown when running the command with
          // the "--help" option
          ->setHelp("Email a user with the diff of a table between two dates")
      ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $ddo = $this->factory->deepDiff();
      #var_dump($ddo->html());
    }

}
