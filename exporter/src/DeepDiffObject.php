<?php

namespace PdoGit;

class DeepDiffObject {

  function __construct(array $differences, \DateTime $d1, \DateTime $d2) {
    $this->differences = $differences;
    $this->d1=$d1;
    $this->d2=$d2;
  }

  // filter differences
  private function split(string $type) {
    return array_filter(
      $this->differences,
      function($entry) use($type) {
        return $entry['type']==$type;
      }
    );
  }

  public function html()
  {
    $loader = new \Twig_Loader_Filesystem(__DIR__.'../twig');
    $twig = new \Twig_Environment($loader)

    $sqlt = $twig->render(
      "differences.html.twig",
      array(
        'edited'=>$this->split('E'),
        'new'=>$this->split('N'),
        'deleted'=>$this->split('D'),
        'd1'=>$d1->format('Y-m-d'),
        'd2'=>$d2->format('Y-m-d')
      )
    );

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
