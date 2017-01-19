<?php

namespace PdoGit;

// split output of db-diff into format for outputting NEW or DELETED or EDITED
class Splitter {

  // differences: output of this->diff
  function __construct(array $differences, Columns $columns=null) {
    $this->differences = $differences;
    $this->columns  = $columns;
  }

  // This function returns row-level subsets
  // "row-level" is important because it doesn't return "new fields"
  //
  // kind: deep-diff key to filter for:
  //       N (new), D (dropped), E (edited)
  //       https://github.com/flitbit/diff#differences
  // columnsKeep - names of columns to filter
  public function split(string $kind) {
    if(!in_array($kind,['N','D','E'])) {
      throw new \Exception("split kind not supported: ".$kind);
    }

    $subset = array_filter(
      $this->differences,
      function($entry) use($kind) {
        return $entry['kind']==$kind;
      }
    );

    if($kind=='E') {
      if(is_null($this->columns)) {
        return $subset;
      }

      $ignore = $this->columns->getEditedIgnored();
      $subset = array_filter(
        $subset,
        function($row) use($ignore) {
          return !in_array($row['path'][1],$ignore);
        }
      );
      return $subset;
    }

    $map = ['N'=>'rhs','D'=>'lhs'];
    $subset = array_column($subset,$map[$kind]);

    if(is_null($this->columns)) {
      return $subset;
    }

    $map = [
      'N'=>$this->columns->getNew(),
      'D'=>$this->columns->getDel()
    ];
    $this->filterCols($subset, $map[$kind]);
    return $subset;
  }

  // FFA-specific filtering of columns
  private function filterCols(array &$out, array $columns) {
      array_walk(
        $out,
        function(&$row) use($columns) {
          $row = array_intersect_key(
            $row,
            array_flip($columns)
          );
        }
      );
  }

}
