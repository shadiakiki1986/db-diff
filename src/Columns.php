<?php

namespace PdoGit;

// Reads a columns.yml file containing
// which column in the table is the ID column,
// which are to be included in the NEW output,
// and which are to be in the DELETED output
class Columns {

  function __construct(string $filename) {
    $this->filename = $filename;
  }

  public function read() {
    $this->data = \yaml_parse_file($this->filename);
  }

  private function newOrDel(string $title) {
    if(!array_key_exists($title,$this->data)) {
      throw new \Exception("Columns YML file '".$this->filename."' is missing field ".$title);
    }
    if(!is_array($this->data[$title])) {
      throw new \Exception($title." field in columns yml file '".$this->filename."' should be an array");
    }
    foreach($this->data[$title] as $entry) {
      if(!is_string($entry)) {
        throw new \Exception("Entry in ".$title." field in columns yml file '".$this->filename."' should be a string");
      }
    }

    return $this->data[$title];
  }

  public function getNew() {
    return $this->newOrDel('new');
  }

  public function getDel() {
    return $this->newOrDel('deleted');
  }

}
