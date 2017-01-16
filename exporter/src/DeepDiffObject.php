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

  public function console(OutputInterface $output)
  {
    $output->writeLn(
      'Diff '
      .$this->d1->format('Y-m-d')
      .' .. '
      .$this->d2->format('Y-m-d')
    );
    $output->writeLn('');

    $output->writeLn('New securities');
    if(count($this->new)==0) {
      $output->writeLn('None');
    } else {
      $table = new Table($output);
      $table->setHeaders(array_keys($this->new[0]));
      $table->setRows($this->new);
      $table->render();
    }
    $output->writeLn('');

    $output->writeLn('Deleted securities');
    if(count($this->deleted)==0) {
      $output->writeLn('None');
    } else {
      $table = new Table($output);
      $table->setHeaders(array_keys($this->deleted[0]));
      $table->setRows($this->deleted);
      $table->render();
    }
    $output->writeLn('');

    $output->writeLn('Edited securities');
    if(count($this->edited)==0) {
      $output->writeLn('None');
    } else {
      $table = new Table($output);
      $table->setHeaders(array_keys($this->edited[0]));
      $table->setRows($this->edited);
      $table->render();
    }
    $output->writeLn('');

  }

}
