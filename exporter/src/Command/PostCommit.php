<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
      $grapiUri = 'http://localhost:8081';
      $git = new \GitRestApi\Client($grapiUri);

      $factory = new \PdoGit\Factory();
      $repo = $factory->repo($git);

      // get diff and send result by email (this is parallel to the UI)
      $commits = $repo->commits();
      $head_1=array_shift($commits);
      $head_2=array_shift($commits);
      $difference = $repo->diff($head_1,$head_2);
      $difference = json_decode($difference,true);

/*      // format to html
      $report = new Report($difference);

      // send by email
      $emailer = new Emailer($report);
      $emailer->send(['my@email.com']);*/
    }

}
