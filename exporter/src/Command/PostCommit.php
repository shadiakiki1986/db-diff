<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
      ;
      parent::configure();
      $this->addOption(
        'format',
        '',
        InputOption::VALUE_REQUIRED,
        'format output: console, json, html',
        'console'
      );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $ddo = $this->factory->deepDiff(
        $input->getArgument('dsn'),
        $input->getArgument('table')
      );

      switch($input->getOption('format')) {
        case 'json':
          $output->writeLn(json_encode($ddo->differences,JSON_PRETTY_PRINT));
          //$output->writeLn(json_encode($ddo->edited,JSON_PRETTY_PRINT));
          break;
        case 'html':
          var_dump($ddo->html());
          break;
        case 'console':
          $ddo->console($output);
          break;
        default:
          throw new \Exception("Invalid format: ".$input->getOption('format'));
      }
    }

}
