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
      $this->configAr = \yaml_parse_file($this->configFn);
    }

    public function send(array $to, string $subject, string $body) {
      return \SwiftmailerWrapper\Utils::mail_attachment(
        [],
        $to,
        $this->configAr['from']['email'],
        $this->configAr['from']['name'],
        $this->configAr['reply'],
        $subject,
        $body,
        $this->configAr['config']
      );
    }
}
