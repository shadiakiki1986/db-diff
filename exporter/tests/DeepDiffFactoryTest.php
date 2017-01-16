<?php

namespace PdoGit;

class DeepDiffFactoryTest extends\PHPUnit_Framework_TestCase 
{

  static public $ROOT = __DIR__.'/data/DeepDiffFactoryTest';

  static public function commitsFixture() {
    $commits = \yaml_parse_file(__DIR__.'/data/commits.yml');
    array_walk(
      $commits,
      function(&$entry) {
        $entry['commitDate'] =  \DateTime::createFromFormat('!Y-m-d?H:i:s.ue',$entry['commitDate']);
      }
    );
    $commits=array_combine(
      array_column($commits,'sha1'),
      $commits
    );
    return $commits;
  }

  public function testParentOfFirstCommitToday() {
    $commits = self::commitsFixture();

    $repo = $this->getMockBuilder('\GitRestApi\Repository')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $repo->method('log')
        ->willReturn($commits);

    $today = \DateTime::createFromFormat('!Y-m-d','2017-01-10');

    $ge = new DeepDiffFactory($repo,'dsn','table');
    $actual = $ge->parentOfFirstCommitToday($commits,$today);
    $expected = '50bf503b16553f7c71a090aa6de79974a6cf1fe3';
    $this->assertEquals($expected,$actual);
  }

  /**
   * @dataProvider testSplitProvider
   */
  public function testSplit(array $type, string $differences, string $expected) {
    $repo = $this->getMockBuilder('\GitRestApi\Repository')
                 ->disableOriginalConstructor() 
                 ->getMock();

    $ge = new DeepDiffFactory($repo,'dsn','table');
    $input = json_decode(
      file_get_contents(self::$ROOT.'/'.$differences),
      true
    );
    $actual = $ge->split($input,$type);
    $actual = json_encode($actual,JSON_PRETTY_PRINT);
    // file_put_contents(self::$ROOT.'/'.$expected,$actual);
    $this->assertJsonStringEqualsJsonFile(
      self::$ROOT.'/'.$expected,
      $actual
    );
  }

  public function testSplitProvider() {
    return [
      [
        ['E'],
        'split_edited_differences.json',
        'split_edited_expected.json'
      ],
      [
        ['A','N'],
        'split_new_differences.json',
        'split_new_expected.json'
      ],
      [
        ['A','D'],
        'split_deleted_differences.json',
        'split_deleted_expected.json'
      ],
    ];
  }

}
