<?php

namespace PdoGit;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class DeepDiffObject {

  // TODO add newFields, droppedFields
  function __construct(array $differences, array $new, array $deleted, array $edited, \DateTime $d1, \DateTime $d2) {
    $this->differences = $differences;
    $this->new = $new;
    $this->deleted = $deleted;
    $this->edited = $edited;

    $this->d1=$d1;
    $this->d2=$d2;
  }

  public function html()
  {
    $loader = new \Twig_Loader_Filesystem(__DIR__.'/twig');
    $twig = new \Twig_Environment($loader);

    $html = $twig->render(
      "differences.html.twig",
      array(
        'edited'=>$this->edited,
        'new'=>$this->new,
        'deleted'=>$this->deleted,
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

  private function consoleCore(OutputInterface $output, string $label, array $array) {
    $output->writeLn($label);
    if(count($array)==0) {
      $output->writeLn('None');
      $output->writeLn('');
      return;
    }

    $table = new Table($output);
    $table->setHeaders(array_keys($array[0]));
    $table->setRows($array);
    $table->render();
    $output->writeLn('');
  }

  public function console(OutputInterface $output)
  {
    $output->writeLn(
      'Diff '
      .$this->d1->format('Y-m-d')
      .' .. '
      .$this->d2->format('Y-m-d')
    );
    $output->writeLn('');

    $this->consoleCore($output,'New securities',$this->new);
    $this->consoleCore($output,'Deleted securities',$this->deleted);

    $processed = [];
    foreach($this->edited as $entry) {
      // skip key changes
      if(!array_key_exists('path',$entry)) continue;

      array_push(
        $processed,
        [
          'ID'=>$entry['path'][0],
          'field'=>$entry['path'][1],
          'old'=>$entry['lhs'],
          'new'=>$entry['rhs']
        ]
      );
    }
    $this->consoleCore($output,'Edited securities',$processed);
  }

}
