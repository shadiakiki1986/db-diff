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
        'format output: console, json, html, email',
        'console'
      );
      $this->addOption(
        'columns',
        '',
        InputOption::VALUE_REQUIRED,
        'columns file: path/to/file.yml'
      );
      $this->addOption(
        'email.to',
        '',
        InputOption::VALUE_REQUIRED,
        'comma-separated list of emails to send to'
      );
      $this->addOption(
        'email.config',
        '',
        InputOption::VALUE_REQUIRED,
        'config yml file with array for swiftmailer-wrapper .. check sample .. https://github.com/shadiakiki1986/swiftmailer-wrapper'
      );
      $this->addOption(
        'email.subject',
        '',
        InputOption::VALUE_REQUIRED,
        'subject to use in email',
        'db-diff'
      );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $emailTo = null;
      $emailConfig = null;
      if($input->getOption('format')=='email') {
        if(!$input->getOption('email.to')) {
          throw new \Exception("Format=email but not email.to provided");
        }
        $emailTo = explode(',',$input->getOption('email.to'));

        if(!$input->getOption('email.config')) {
          throw new \Exception("Format=email but not email.config provided");
        }

        if(!$input->getOption('email.subject')) {
          throw new \Exception("Format=email but not email.subject provided");
        }
      }

      $ddo = $this->factory->deepDiff(
        $input->getArgument('dsn'),
        $input->getArgument('table'),
        $input->getOption('columns')
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
        case 'email':
          $emailer = new \PdoGit\Emailer($input->getOption('email.config'));
          $emailer->readConfig();
          $emailer->send(
            $emailTo,
            $input->getOption('email.subject'),
            $ddo->html()
          );
          break;
        default:
          throw new \Exception("Invalid format: ".$input->getOption('format'));
      }
    }

}
