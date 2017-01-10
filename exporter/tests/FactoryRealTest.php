<?php

namespace PdoGit;

class FactoryRealTest extends\PHPUnit_Framework_TestCase 
{

  static public $repo;

  static public function setUpBeforeClass() {
    // import a fake table yml file
    $factory = new \PdoGit\Factory();
    self::$repo = $factory->repo();
    self::$repo->putConfig('user.email','PostCommitRealGitTest@phpunit.com');
    self::$repo->putConfig('user.name','PostCommitRealGitTest phpunit');

    $table = [
      'tit1'=>['field11'=>'val11','field12'=>'val12'],
      'tit2'=>['field21'=>'val21','field22'=>'val22']
    ];
    self::$repo->putTree('TITRE.yml',\yaml_emit($table));
    self::$repo->postCommit('first commit');
    // make a fake table update
    $table['tit1']['field11']='val11 prime';
    self::$repo->putTree('TITRE.yml',\yaml_emit($table));
    self::$repo->postCommit('second commit');
    // make another fake table update
    $table['tit1']['field11']='val11 double prime';
    self::$repo->putTree('TITRE.yml',\yaml_emit($table));
    self::$repo->postCommit('third commit');
  }

  public function testDeepDiff() {
    $fac = new Factory();
    $ddo = $fac->deepDiff();
    $this->assertInstanceOf(\PdoGit\DeepDiffObject::class,$ddo);
    $this->assertEquals([],$ddo->differences);
  }

}
