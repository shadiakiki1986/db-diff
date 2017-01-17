<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

// http://symfony.com/doc/current/console.html#testing-commands
class ExportTest extends\PHPUnit_Framework_TestCase 
{

    static $sqlite;

    static public function setUpBeforeClass() {
        // prepare dummy data
        self::$sqlite = new \PDO("sqlite::memory:");
        self::$sqlite->exec('create table test (id Integer);');
        self::$sqlite->exec('INSERT INTO test (id) values (1);');
    }

    static public function tearDownAfterClass() {
        self::$sqlite->exec('drop table test;');
    }

    public function testExecute()
    {

        // create mocks
        $factory = $this->getMockBuilder('\PdoGit\Factory')
                     ->disableOriginalConstructor() 
                     ->getMock();
        $factory->method('pdo')
            ->will($this->returnCallback(function () {
              yield 'test'=>self::$sqlite;
            }));

        $repo = $this->getMockBuilder('\GitRestApi\Repository')
                     ->disableOriginalConstructor() 
                     ->getMock();
        $repo->method('diff')->willReturn('bla');
        $factory->method('repo')
            ->willReturn($repo);

        // launch app
        $application = new Application();
        $application->add(new Export($factory));

        $command = $application->find('export');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'dsn'     => 'test',
            'table'   => 'test',
            'ID'      => 'id'
        ));

        // the output of the command in the console
        //$output = $commandTester->getDisplay();
        //$this->assertContains('Username: Wouter', $output);
    }
}
