<?php

// prepare d/b access
    $odbcIni = new OdbcIni();
    $fac = new DbhWrapperFactory();

    $ini = $odbcIni->parse();

// prepare git access
$GIT_NAME='MfDbVersioned';

$git        = new GitRestApi\Client('http://localhost:8081');
$repo = $git->get($GIT_NAME);
if(!$repo) {
  // if first usage
  $repo = $git->init('MfDbVersioned');
}

// iterate over databases in odbc
    foreach($ini as $name=>$value) {
      if($verbose) {
        echo "Copying locks to $name".PHP_EOL;
      }
      $dbh = $fac->odbc($name);

      $result = $dbh->query("select * from TITRE");

      // update a file called 'filename' in the repository
      $repo->put($name.'/TITRE.yml',\yaml_emit($result));
    }

// commit the changes
$repo->commit('a new commit message');
// tag
// no need to tag since relying on commit dates
// $repo->tag(date('Y-m-d H:M:i'));
