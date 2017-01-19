<?php

namespace PdoGit;

class Factory {

  public function repo(\GitRestApi\Client $git=null) {

    if(is_null($git)) {
      $grapiUri = getenv('DBDIFF_GRAPI_HOST');
      if(!$grapiUri) {
        throw new \Exception('Missing env var DBDIFF_GRAPI_HOST, e.g. export DBDIFF_GRAPI_HOST="http://localhost:8081"');
      }
      $git = new \GitRestApi\Client($grapiUri);
    }

    // prepare git access
    $GIT_NAME='MfDbVersioned';
    $repo = $git->get($GIT_NAME);
    if(!$repo) {
      // if first usage
      $repo = $git->init($GIT_NAME);
      $repo->putConfig('user.email','shadiakiki1986@gmail.com');
      $repo->putConfig('user.name','Shadi Akiki');
    }
    return $repo;
  }

  // dsn: array of strings of DSN names in /etc/odbc.ini
  public function pdo(array $dsn, string $iniFile = '/etc/odbc.ini', PdoWrap $pdoWrap=null) {

    if(is_null($pdoWrap)) $pdoWrap=new PdoWrap();

    // iterate over databases in odbc
    $iniContents = parse_ini_file($iniFile,true);
    $wrong = array_diff_key(array_flip($dsn),$iniContents);
    if(count($wrong)>0) {
      throw new \Exception(
        "Found invalid DSN: "
        .implode(', ',$wrong)
        .". Valids: "
        .implode(', ',array_keys($iniContents))
      );
    }
    $iniContents = array_intersect_key($iniContents,array_flip($dsn));

    # $dsn = 'MarketflowAcc';
    foreach($iniContents as $dsn=>$details) {
      if(!array_key_exists('UID',$details)) {
        throw new \Exception("Missing UID from $dsn in ".$iniFile);
      }

      $pdo = $pdoWrap->get($dsn,$details['UID'],$details['PWD']);

      #$dsn = "mysql:host=$hostname;dbname=$dbname";
      #return new \PDO($dsn, $username, $password);

      yield $dsn=>$pdo;
    }
  }

  // columnsYml - path to yml file defining which columns are to be shown for NEW and which for DELETED
  public function deepDiff(string $dsn, string $table, string $columnsYml=null) {
    $repo = $this->repo();
    $ge = new DeepDiffFactory($repo,$dsn,$table);

    // get history of commits
    $commits = $ge->commits();

    // key by sha1
    $commits = array_combine(
      array_column($commits,'sha1'),
      $commits
    );

    // get sha1 of commit to diff by
    $today = new \DateTime();
    $sha1 = $ge->parentOfFirstCommitToday($commits,$today);

    // get diff
    $differences = $ge->diff($sha1);

    // get columns identifier
    $cg = null;
    if(!is_null($columnsYml)) {
      $cg = new Columns($differences,$columnsYml);
      $cg->read();
    }

    // preprocess
    $splitter = new Splitter($differences,$cg);

    return new DeepDiffObject(
      $differences,
      //$ge->split($differences,['A','N']),
      //$ge->split($differences,['A','D']),

      $cg->split('N'),
      $cg->split('D'),
      $cg->split('E'),
      $commits[$sha1]['commitDate'],
      $today
    );
  }

}
