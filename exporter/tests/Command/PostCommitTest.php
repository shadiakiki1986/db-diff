<?php

namespace PdoGit\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

// http://symfony.com/doc/current/console.html#testing-commands
class PostCommitTest extends\PHPUnit_Framework_TestCase 
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new PostCommit());

        $command = $application->find('post-commit');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName()
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Username: Wouter', $output);
    }
}
