<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Command\Command as SymCommand;

class Command extends SymCommand {

    public function __construct(\PdoGit\Factory $factory=null)
    {
      if(is_null($factory)) {
        $factory = new \PdoGit\Factory();
      }

      $this->factory = $factory;

      parent::__construct();
    }

}
