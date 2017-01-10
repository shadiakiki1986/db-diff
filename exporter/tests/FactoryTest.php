<?php

namespace PdoGit;

class FactoryTest extends \PHPUnit_Framework_TestCase {

  public function testPdo() {
    $pdoWrap = $this->getMockBuilder('\PdoGit\PdoWrap')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $pdoWrap->method('get')
        ->willReturn(new \PDO("sqlite::memory:"));

    $fac = new Factory();
    $obj = $fac->pdo(__DIR__.'/odbc.ini',$pdoWrap);

    // unwrap the object returned
    $obj = iterator_to_array($obj);
    $obj=array_pop($obj);
    $this->assertInstanceOf(\PDO::class,$obj['pdo']);
    $this->assertTrue(is_array($obj['odbc']));
  }

  public function testRepo() {
    $repo = $this->getMockBuilder('\GitRestApi\Repository')
                 ->disableOriginalConstructor() 
                 ->getMock();

    $git = $this->getMockBuilder('\GitRestApi\Client')
                 ->disableOriginalConstructor() 
                 ->getMock();
#    $git->method('get')
#        ->willReturn($repo);
    $git->method('init')
        ->willReturn($repo);

    $fac = new Factory();
    $repo = $fac->repo($git);
    $this->assertInstanceOf(\GitRestApi\Repository::class,$repo);
  }

} // end class
