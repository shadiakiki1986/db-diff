<?php

namespace PdoGit;

class PdoGit {

  function __construct(\PDO $pdo, \GitRestApi\Repository $repo) {
    $this->pdo = $pdo;
    $this->repo = $repo;
  }

  public function export(string $dsn, string $table, string $id, bool $init=false) {
    $stmt = $this->pdo->query("select * from ".$table,\PDO::FETCH_ASSOC);
    if(!$stmt) {
      throw new \Exception("Exception from PDO query");
    }

    $result = $stmt->fetchAll();
    if(!$result) {
      throw new \Exception("No data returned from PDO query");
    }

    // keying by ID field
    $result = array_combine(
      array_column($result,$id),
      $result
    );

    // upload to git (without committing)
    $fn = $dsn.'/'.$table.'.yml';
    $this->repo->putTree($fn,\yaml_emit($result));

    // before committing, check if data changed
    if(!$init) {
      if($this->repo->diff($fn,null,null,true)=='') {
        throw new \Exception("Data not changed");
      }
    }

    // commit
    $this->repo->postCommit('a new commit message');
  }

}
