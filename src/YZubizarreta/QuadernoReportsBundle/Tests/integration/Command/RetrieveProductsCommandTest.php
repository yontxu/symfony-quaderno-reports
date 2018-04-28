<?php
namespace YZubizarreta\QuadernoReportsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RetrieveProductsCommandTest extends KernelTestCase
{

    protected $apiReportsMock;
    protected $mailerMock;

    public function setUp()
    {
        parent::setUp();
        $this->apiReportsMock = $this->createMock(\YZubizarreta\QuadernoReportsBundle\Adapter\Quaderno::class);
        $this->mailerMock = $this->createMock(\Swift_Mailer::class);
    }

    public function testExecute()
    {
        $from = '2018-01-01';
        $to = '2018-01-01';

        $this->apiReportsMock->expects($this->any())
            ->method('connect')
            ->willReturn(true);

        $this->apiReportsMock->expects($this->any())
            ->method('getInvoicesByDate')
            ->willReturn(
                array($this->createMockInvoice($from))
            );

        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(
            new RetrieveProductsCommand(
                $this->apiReportsMock,
                array('email' => array('from' => 'from@mail.com', 'to' => 'to@mail.com')),
                $this->mailerMock
            )
        );

        $command = $application->find('quaderno-reports:invoices:retrieve-line-items');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            '--from' => $from,
            '--to' => $to
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        //var_dump($output);
        $this->assertContains('Retrieving invoices from ['.$from.'] to ['.$to.']', $output);
    }

    public function createMockInvoice($date){
        $item = array('reference' => 'test', 'quantity' => 1);
        $invoice = new \stdClass();
        $invoice->issue_date = $date;
        $invoice->items = array($item);
        return $invoice;
    }
}