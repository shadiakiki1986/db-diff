<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputOption;

// get diff and send result by email (this is parallel to the UI)
class PostCommit extends MyCommand {

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
          ->addOption(
              'format',
              '',
              InputOption::VALUE_REQUIRED,
              'format output: console, php, html',
              'console'
            )
      ;
      parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $ddo = $this->factory->deepDiff($input->getArgument('dsn'),$input->getArgument('table'));

      switch($input->getOption('format')) {
        case 'php':
          var_dump($ddo->split('A'));
          break;
        case 'html':
          var_dump($ddo->html());
          break;
        case 'console':
          $table = new Table($output);
          $table->setHeaders(array_keys($ddo->split('A')[0]));
          $table->setRows($ddo->split('A'));
          $table->render();
          break;
        default:
          throw new \Exception("Invalid format: ".$input->getOption('format'));
      }
    }

}
