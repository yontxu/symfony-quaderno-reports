<?php
namespace YZubizarreta\QuadernoReportsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RetrieveProductsCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(new RetrieveProductsCommand());

        $command = $application->find('quaderno:retrieve-products');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),

            // pass arguments to the helper
            '--from' => '2018-01-01',
            '--to' => '2018-01-02'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        //var_dump($output);
        $this->assertContains('Retrieving invoices from [2018-01-01] to [2018-01-02]', $output);
    }
}