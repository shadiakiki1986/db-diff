<?php

namespace PdoGit;

class PdoGit {

  function __construct(\PDO $pdo, \GitRestApi\Repository $repo) {
    $this->pdo = $pdo;
    $this->repo = $repo;
  }

  public function export(string $table, string $prefix) {
    $result = $this->pdo->query("select * from ".$table);
    $result = iterator_to_array($result);
    $this->repo->putConfig('user.email','shadiakiki1986@gmail.com');
    $this->repo->putConfig('user.name','Shadi Akiki');
    $this->repo->putTree($prefix.'/'.$table.'.yml',\yaml_emit($result));
    $this->repo->postCommit('a new commit message');
  }

}
