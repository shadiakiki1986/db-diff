<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class MyCommand extends Command {

    public function __construct(\PdoGit\Factory $factory=null)
    {
      if(is_null($factory)) {
        $factory = new \PdoGit\Factory();
      }

      $this->factory = $factory;

      parent::__construct();
    }

    protected function configure()
    {
      $this
          ->addArgument(
            'dsn',
            InputArgument::REQUIRED,
            'DSN in /etc/odbc.ini to export'
          )
          ->addArgument(
            'table',
            InputArgument::REQUIRED,
            'Table in DSN to export. Prefix with databasename, e.g. Marketflow..TITRE for MS SQL Server, Marketflow.TITRE for MySql'
          )
        ;
    }
}
