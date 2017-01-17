<?php

namespace PdoGit;

class PdoGitTest extends \PHPUnit_Framework_TestCase {

  public function testExport() {
    $pdo = $this->getMockBuilder('\PDO')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $pdo->method('query')
        ->will($this->returnCallback(function() {
          yield [
            ['c11'=>'v11','c12'=>'v12'],
            ['c21'=>'v21','c21'=>'v21']
          ];
        }));

    $repo = $this->getMockBuilder('\GitRestApi\Repository')
                 ->disableOriginalConstructor() 
                 ->getMock();

    $pg = new PdoGit($pdo,$repo);
    $pg->export('table','prefix');
  }

} // end class
