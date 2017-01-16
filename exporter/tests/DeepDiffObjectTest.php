<?php

namespace PdoGit;

class DeepDiffObjectTest extends\PHPUnit_Framework_TestCase 
{

  static public $ROOT_F = __DIR__.'/data/DeepDiffFactoryTest';
  static public $ROOT_O = __DIR__.'/data/DeepDiffObjectTest';

  /**
   * @dataProvider testHtmlProvider
   */
  public function testHtml(array $new, array $deleted, array $edited, string $expected) {
    $ge = new DeepDiffObject(
      [],
      $new,
      $deleted,
      $edited,
      new \DateTime(),
      new \DateTime()
    );
    $actual = $ge->html();
    file_put_contents($expected,$actual);
    $this->assertEquals(
      file_get_contents($expected),
      $actual
    );
  }

  private function jsonGetRootf(string $val) {
    return json_decode(
      file_get_contents(self::$ROOT_F.'/'.$val),
      true
    );
  }

  public function testHtmlProvider() {
    return [
      [
        [],
        [],
        $this->jsonGetRootf('split_edited_expected.json'),
        self::$ROOT_O.'/html_edited.html'
      ],
      [
        [],
        $this->jsonGetRootf('split_deleted_expected.json'),
        [],
        self::$ROOT_O.'/html_deleted.html'
      ],
      [
        $this->jsonGetRootf('split_new_expected.json'),
        [],
        [],
        self::$ROOT_O.'/html_new.html'
      ]
    ];
  }

}
