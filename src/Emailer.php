<?php

namespace PdoGit;

// Handler for sending email
// https://github.com/shadiakiki1986/swiftmailer-wrapper
class Emailer {

    function __construct(string $configFn)
    {
      $this->configFn = $configFn;
    }

    public function readConfig()
    {
      $conf = \yaml_parse_file($this->configFn);
      $should_be_arrays = ['to','config','from'];
      foreach($should_be_arrays as $sba) {
        if(!is_array($conf[$sba])) {
          throw new \Exception(
            sprintf(
              "Config '%s' invalid field '%s': should be array",
              $this->configFn,
              $sba
            )
          );
        }
      }

      $this->configAr = $conf;
    }

    public function send(string $subject, string $body) {
      return \SwiftmailerWrapper\Utils::mail_attachment(
        [],
        $this->configAr['to'],
        $this->configAr['from']['email'],
        $this->configAr['from']['name'],
        $this->configAr['reply'],
        $subject,
        $body,
        $this->configAr['config']
      );
    }
}
