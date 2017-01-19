<?php

namespace PdoGit;

class SplitterTest extends\PHPUnit_Framework_TestCase 
{

  static public $ROOT = __DIR__.'/data/Splitter';

  /**
   * @dataProvider testSplitProvider
   */
  public function testSplit(string $kind, string $differences, string $expected, Columns $cg=null) {

    $input = json_decode(
      file_get_contents(self::$ROOT.'/'.$differences),
      true
    );

    $ge = new Splitter($input, $cg);
    $actual = $ge->split($kind);
    $actual = json_encode($actual,JSON_PRETTY_PRINT);
    // file_put_contents(self::$ROOT.'/'.$expected,$actual);
    $this->assertJsonStringEqualsJsonFile(
      self::$ROOT.'/'.$expected,
      $actual
    );

  }

  public function testSplitProvider() {
    $cg = new Columns(__DIR__.'/../src/columns/test.yml');
    $cg->read();
    return [
      [
        'E',
        'splitColsNull_edited_differences.json',
        'splitColsNull_edited_expected.json',
        null
      ],
      [
        'N',
        'splitColsNull_new_differences.json',
        'splitColsNull_new_expected.json',
        null
      ],
      [
        'D',
        'splitColsNull_deleted_differences.json',
        'splitColsNull_deleted_expected.json',
        null
      ],
      // -----------------
      [
        'E',
        'splitColsFfaTitre_edited_differences.json',
        'splitColsFfaTitre_edited_expected.json',
        $cg
      ],
      [
        'N',
        'splitColsFfaTitre_new_differences.json',
        'splitColsFfaTitre_new_expected.json',
        $cg
      ],
      [
        'D',
        'splitColsFfaTitre_deleted_differences.json',
        'splitColsFfaTitre_deleted_expected.json',
        $cg
      ],

    ];
  }

}
