<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

// http://symfony.com/doc/current/console.html#testing-commands
class ExportTest extends\PHPUnit_Framework_TestCase 
{
    public function testExecute()
    {
        $factory = $this->getMockBuilder('\PdoGit\Factory')
                     ->disableOriginalConstructor() 
                     ->getMock();
        $factory->method('pdo')
            ->will($this->returnCallback(function () {
              yield ['pdo'=>new \PDO("sqlite::memory:"),'odbc'=>['dbname'=>'test']];
            }));

        $repo = $this->getMockBuilder('\GitRestApi\Repository')
                     ->disableOriginalConstructor() 
                     ->getMock();
        $factory->method('repo')
            ->willReturn($repo);


        $application = new Application();
        $application->add(new Export($factory));

        $command = $application->find('export');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName()
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Username: Wouter', $output);
    }
}
