<?php

namespace PdoGit;

class Factory {

  public function repo(\GitRestApi\Client $git) {
    // prepare git access
    $GIT_NAME='MfDbVersioned';
    $repo = $git->get($GIT_NAME);
    if(!$repo) {
      // if first usage
      $repo = $git->init($GIT_NAME);
    }
    return $repo;
  }

  public function pdo(string $iniFile = '/etc/odbc.ini', PdoWrap $pdoWrap) {

    // iterate over databases in odbc
    $iniContents = parse_ini_file($iniFile,true);

    # $dsn = 'MarketflowAcc';
    foreach($iniContents as $dsn=>$details) {

      $pdo = $pdoWrap->get($dsn,$details['UID'],$details['PWD']);

      #$dsn = "mysql:host=$hostname;dbname=$dbname";
      #return new \PDO($dsn, $username, $password);

      yield $dsn=>['pdo'=>$pdo,'odbc'=>$details];
    }
  }

  public function pdoCore(string $dsn, string $UID, string $PWD) {
    return new \PDO("odbc:$dsn",$UID,$PWD);
  }

}
