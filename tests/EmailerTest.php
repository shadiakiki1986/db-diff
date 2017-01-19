<?php

namespace PdoGit;

class EmailerTest extends\PHPUnit_Framework_TestCase 
{

  public function test() {
    $fn = __DIR__.'/../src/swiftmailer/ffa.yml';
    if(!file_exists($fn)) {
      $this->markTestSkipped("ffa swiftmailer config file not available");
    }

    $ge = new Emailer($fn);
    $ge->readConfig();
    $ge->send(
      $ge->configAr['from']['email'],
      'test email',
      'test email'
    );
  }
}
