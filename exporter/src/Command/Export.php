<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Export extends Command {


    protected function configure()
    {
      $this
          // the name of the command (the part after "bin/console")
          ->setName('export')

          // the short description shown while running "php bin/console list"
          ->setDescription('Export a sql server table to git')

          // the full command description shown when running the command with
          // the "--help" option
          ->setHelp("Export a sql server table to git")
      ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $iniFile = '/etc/odbc.ini';
      $grapiUri = 'http://localhost:8081';
      $git = new \GitRestApi\Client($grapiUri);

      $factory = new \PdoGit\Factory();
      $repo = $factory->repo($git);

      foreach($factory->pdo($iniFile) as $dsn=>$obj) {
        $obj['pdo']->query("use ".$obj['odbc']['dbname'].";");
        $pg = new \PdoGit\PdoGit($obj['pdo'],$repo);
        $pg->export('TITRE',$dsn);
      }
    }

}
