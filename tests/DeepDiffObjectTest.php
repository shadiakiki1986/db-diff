<?php

namespace PdoGit;

class DeepDiffObjectTest extends\PHPUnit_Framework_TestCase 
{

  static public $ROOT_F = __DIR__.'/data/SplitterTest';
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
      \DateTime::createFromFormat('!Y-m-d','2017-01-16'),
      \DateTime::createFromFormat('!Y-m-d','2017-01-16')
    );
    $actual = $ge->html();
    // file_put_contents($expected,$actual);

    // Drop spaces
    // http://stackoverflow.com/a/7172153/4126114
    $expectedDom = new \DomDocument();
    $expectedDom->loadHTMLFile($expected);
    $expectedDom->preserveWhiteSpace = false;

    $actualDom = new \DomDocument();
    $actualDom->loadHTML($actual);
    $actualDom->preserveWhiteSpace = false;

    // assert equal
    $this->assertXmlStringEqualsXmlString(
      $expectedDom->saveHTML(),
      $actualDom->saveHTML(),
      $expected
    );
  }

  private function jsonGetRootf(string $val) {
    $fn = self::$ROOT_F.'/'.$val;
    if(!file_exists($fn)) {
      throw new \Exception("File inexistant: ".$fn);
    }

    return json_decode(
      file_get_contents($fn),
      true
    );
  }

  public function testHtmlProvider() {
    return [
      [
        [],
        [],
        $this->jsonGetRootf('splitColsNull_edited_expected.json'),
        self::$ROOT_O.'/html_edited.html'
      ],
      [
        [],
        $this->jsonGetRootf('splitColsNull_deleted_expected.json'),
        [],
        self::$ROOT_O.'/html_deleted.html'
      ],
      [
        $this->jsonGetRootf('splitColsNull_new_expected.json'),
        [],
        [],
        self::$ROOT_O.'/html_new.html'
      ]
    ];
  }

}
