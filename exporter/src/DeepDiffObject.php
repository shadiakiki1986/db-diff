<?php

namespace PdoGit;

class DeepDiffObject {

  function __construct(array $differences) {
    $this->differences = $differences;
  }

  public function html()
  {
  }

  public function email()
  {
  }

/*      // format to html
    $report = new Report($difference);

    // send by email
    $emailer = new Emailer($report);
    $emailer->send(['my@email.com']);*/

}
