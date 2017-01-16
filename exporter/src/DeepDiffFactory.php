<?php

namespace PdoGit;

// get diff and prepare result for email (this is parallel to the UI)
class DeepDiffFactory {

  function __construct(\GitRestApi\Repository $repo, string $dsn, string $table) {
    $this->repo = $repo;
    $this->dsn  = $dsn;
    $this->table= $table;
  }

  public function commits()
  {
    $commits = $this->repo->log();
    $commits = array_map(function($x) { return (array)$x;},$commits);

    // save for debugging
    // \yaml_emit_file(__DIR__.'/../../tests/Command/data/commits.json',$commits);

    return $commits;
  }

  // find 1st commit made today, then take its parent
  // and return sha1 of commit to diff by
  //
  // commits - output of this->commits
  // today   - user-defined
  public function parentOfFirstCommitToday(array $commits, \DateTime $today) {
    if(count($commits)==0) {
      throw new \Exception("No commits in history");
    }

    $commits=array_combine(
      array_column($commits,'sha1'),
      $commits
    );
    $sha1A=array_combine(
      array_column($commits,'sha1'),
      array_column($commits,'commitDate')
    );

    $sha1A = array_filter(
      $sha1A,
      function($entry) use($today) {
        return $entry->format('Y-m-d') == $today->format('Y-m-d');
      }
    );

    if(count($sha1A)==0) {
      throw new \Exception("No commits today: ".$today->format('Y-m-d'));
    }

    asort($sha1A);
    $sha1F = array_keys($sha1A);
    $sha1F=array_shift($sha1F);
    $sha1Y=$commits[$sha1F]['parents'];

    // if more than one parent
    if(count($sha1Y)>1) {
      $sha1Y = array_slice($sha1Y,0,1); // take first
    }
    $sha1Y = array_pop($sha1Y);

    // if there is indeed a commit before today, return it
    if(!is_null($sha1Y)) {
      return $sha1Y;
    }

    // otherwise return the first commit today
    return $sha1F;
  }

  // sha1 - output of $this->parentOf...
  public function diff(string $sha1) {
    $diffS = $this->repo->diff($this->dsn.'/'.$this->table.'.yml',$sha1);

    if($diffS=='') {
      throw new \Exception("git diff returned empty string!");
    }

    $diffA = json_decode($diffS,true);

    if(is_null($diffA)) {
      throw new \Exception(
        $diffS
        .PHP_EOL
        ."git diff did not return json.. did you forget to run git-rest-api from the ffa-database-diff/git dockerfile?"
      );
    }

    return($diffA);
  }

}
