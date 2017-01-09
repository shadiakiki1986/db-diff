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
$result = [];
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

//###########################

// get diff and send result by email (this is parallel to the UI)
$commits = $repo->commits();
$head_1=array_shift($commits);
$head_2=array_shift($commits);
$difference = $repo->diff($head_1,$head_2);
$difference = json_decode($difference,true);

// format to html
$report = new Report($difference);

// send by email
$emailer = new Emailer($report);
$emailer->send(['my@email.com']);
