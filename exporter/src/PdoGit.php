<?php

namespace PdoGit;

class PdoGit {

  function __construct(\PDO $pdo, \GitRestApi\Repository $repo) {
    $this->pdo = $pdo;
    $this->repo = $repo;
  }

  public function export(string $table, string $prefix) {
    $result = $this->pdo->query("select * from ".$table);
    $this->repo->putTree($prefix.'/'.$table.'.yml',\yaml_emit($result));
    $this->repo->postCommit('a new commit message');
  }

}
