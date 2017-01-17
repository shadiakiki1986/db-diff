<?php

namespace PdoGit;
use phpmock\MockBuilder;

class PdoGitTest extends \PHPUnit_Framework_TestCase {

  public function testExport() {
    $stmt = $this->getMockBuilder('\PDOStatement')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $stmt->method('fetchAll')
        ->will($this->returnCallback(function() {
          return [
            ['c1'=>'v11','c2'=>'v12'],
            ['c1'=>'v21','c2'=>'v21']
          ];
        }));

    $pdo = $this->getMockBuilder('\PDO')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $pdo->method('query')
        ->willReturn($stmt);

    $repo = $this->getMockBuilder('\GitRestApi\Repository')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $repo->method('diff')->willReturn('bla');

    // https://github.com/php-mock/php-mock
    $builder = new MockBuilder();
    $builder->setNamespace(__NAMESPACE__)
            ->setName("getenv")
            ->setFunction(
                function () {
                    return 1417011228;
                }
            );

    $pg = new PdoGit($pdo,$repo);
    $pg->export('table','prefix','c1');
  }

} // end class
