<?php

namespace PdoGit;

class FactoryRealTest extends\PHPUnit_Framework_TestCase 
{

  static public $repo;

  static public function setUpBeforeClass() {
    if(!getenv('DBDIFF_GRAPI_HOST')) {
      self::markTestSkipped("Please define env var DBDIFF_GRAPI_HOST");
    }

    // import a fake table yml file
    $factory = new \PdoGit\Factory();
    self::$repo = $factory->repo();
    self::$repo->putConfig('user.email','PostCommitRealGitTest@phpunit.com');
    self::$repo->putConfig('user.name','PostCommitRealGitTest phpunit');

    $table = [
      'tit1'=>['field11'=>'val11','field12'=>'val12'],
      'tit2'=>['field21'=>'val21','field22'=>'val22']
    ];
    $fn = 'MarketflowAcc/TITRE.yml';
    self::$repo->putTree($fn,\yaml_emit($table));
    self::$repo->postCommit('first commit');
    // make a fake table update
    $table['tit1']['field11']='val11 prime';
    self::$repo->putTree($fn,\yaml_emit($table));
    self::$repo->postCommit('second commit');
    // make another fake table update
    $table['tit1']['field11']='val11 double prime';
    self::$repo->putTree($fn,\yaml_emit($table));
    self::$repo->postCommit('third commit');
  }

  static public function tearDownAfterClass() {
    self::$repo->deleteAll();
  }

  public function testDeepDiff() {
    $fac = new Factory();
    $ddo = $fac->deepDiff();
    $this->assertInstanceOf(\PdoGit\DeepDiffObject::class,$ddo);

    $expFn = __DIR__.'/data/differences.yml';
    # \yaml_emit_file($expFn,$ddo->differences);
    $expVal = \yaml_parse_file($expFn);

    $this->assertEquals($expVal,$ddo->differences);
  }

}
