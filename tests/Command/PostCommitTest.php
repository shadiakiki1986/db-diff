<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

// http://symfony.com/doc/current/console.html#testing-commands
class PostCommitMockedTest extends\PHPUnit_Framework_TestCase 
{

  public function testExecute()
  {
    $ddo = $this->getMockBuilder('\PdoGit\DeepDiffObject')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $ddo->differences = [
    ];

    $factory = $this->getMockBuilder('\PdoGit\Factory')
                 ->disableOriginalConstructor() 
                 ->getMock();
    $factory->method('deepDiff')
        ->willReturn($ddo);

    $application = new Application();
    $application->add(new PostCommit($factory));

    $command = $application->find('post-commit');
    $commandTester = new CommandTester($command);
    $commandTester->execute(array(
              'command' => $command->getName(),
                  'dsn' => 'test',
                'table' => 'test',
            '--columns' => __DIR__.'/../../src/columns/ffa-titre.yml',
           '--email.to' => 'shadiakiki1986@gmail.com',
      '--email.subject' => 'some random email'
    ));

    // the output of the command in the console
    //$output = $commandTester->getDisplay();
    //$this->assertContains('Username: Wouter', $output);
  }

}
