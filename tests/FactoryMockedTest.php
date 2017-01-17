<?php

namespace PdoGit;

class FactoryMockedTest extends \PHPUnit_Framework_TestCase {

  public function testPdoOk() {
    $pdoWrap = $this->getMockBuilder('\PdoGit\PdoWrap')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $pdoWrap->method('get')
        ->willReturn(new \PDO("sqlite::memory:"));

    $fac = new Factory();
    $pdo = $fac->pdo(['MarketflowAcc'],__DIR__.'/data/odbc.ini',$pdoWrap);

    // unwrap the pdo object returned
    $pdo = iterator_to_array($pdo);
    $this->assertEquals(1,count($pdo));
    $pdo=array_pop($pdo);
    $this->assertInstanceOf(\PDO::class,$pdo);
  }

  public function testPdoInvalidDsn() {
    $pdoWrap = $this->getMockBuilder('\PdoGit\PdoWrap')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $pdoWrap->method('get')
        ->willReturn(new \PDO("sqlite::memory:"));

    $fac = new Factory();
    $pdo = $fac->pdo(['wrong'],__DIR__.'/data/odbc.ini',$pdoWrap);

    // unwrap the pdo object returned
    $this->expectException(\Exception::class);
    $pdo = iterator_to_array($pdo);
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
