<?php

namespace PdoGit;

class DeepDiffObject {

  function __construct(array $differences, \DateTime $d1, \DateTime $d2) {
    $this->differences = $differences;
    $this->d1=$d1;
    $this->d2=$d2;
  }

  // filter differences
  public function split(string $kind) {
    $out = array_filter(
      $this->differences,
      function($entry) use($kind) {
        return $entry['kind']==$kind;
      }
    );

    if($kind=='A') {
      $out = array_column($out,'item');
      $out = array_column($out,'rhs');
      array_walk(
        $out,
        function(&$row) {
          $row = array_intersect_key(
            $row,
            array_flip(['TIT_COD','TIT_NOM','TIT_REU_COD'])
          );
        }
      );
    }

    return $out;
  }

  public function html()
  {
    $loader = new \Twig_Loader_Filesystem(__DIR__.'/twig');
    $twig = new \Twig_Environment($loader);

    $html = $twig->render(
      "differences.html.twig",
      array(
        'edited'=>$this->split('E'),
        'new'=>$this->split('A'),
        'deleted'=>$this->split('D'),
        'd1'=>$this->d1->format('Y-m-d'),
        'd2'=>$this->d2->format('Y-m-d')
      )
    );
    return $html;
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
