<?php

namespace PdoGit;

class PdoWrap {

  public function get(string $dsn, string $UID, string $PWD) {
    return new \PDO("odbc:$dsn",$UID,$PWD);
  }

}
