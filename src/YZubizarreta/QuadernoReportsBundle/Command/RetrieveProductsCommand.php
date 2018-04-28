<?php
/**
 * Created by PhpStorm.
 * User: yzubizarreta
 * Date: 13/10/17
 * Time: 17:25
 */

namespace YZubizarreta\QuadernoReportsBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use YZubizarreta\QuadernoReportsBundle\Adapter\ApiReportsInterface;

class RetrieveProductsCommand extends AbstractQuadernoReportsCommand
{

    /**
     * @var \YZubizarreta\QuadernoReportsBundle\Adapter\ApiReportsInterface
     */
    private $api_reports;

    /*
     * Quaderno configuration
     *
     * @var array
     */
    protected $configuration;

    public function __construct(
        ApiReportsInterface $api_reports,
        array $configuration,
        \Swift_Mailer $mailer,
        string $name = null
    )
    {
        parent::__construct($mailer, $name);

        $this->api_reports = $api_reports;
        $this->configuration = $configuration;
    }

    protected function configure()
    {
        $this
                ->setName('quaderno-reports:invoices:retrieve-line-items')

                ->setDescription('Retrieve Products by date range.')

                ->setHelp('This command allows you to retrieve products by a given date range.')
                ->addOption(
                    'from',
                    null,
                    InputOption::VALUE_REQUIRED,
                    'Start date of report range.'
                )
                ->addOption(
                    'to',
                    null,
                    InputOption::VALUE_OPTIONAL,
                    'End date of report range.'
                )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->getFormatter()->setStyle('notice', new OutputFormatterStyle('red', 'yellow'));

        //$config = array();//$this->getContainer()->getParameter('yz_quaderno_reports.config');
        if($this->api_reports->connect()){
            $from = $input->getOption('from');
            $to = $input->getOption('to');
            $email_from = $this->configuration['email']['from'];
            $email_to = $this->configuration['email']['to'];
            $report_name = 'sales_report_'.$from.'-'.$to.'.csv';
            $email_subject = 'Sales Report: ['.$from.'] to ['. $to . ']';
            $output->writeln(
                sprintf(
                    'Retrieving invoices from [%s] to [%s]',
                    $from,
                    $to
                )
            );

            $invoices_total = array();
            $csv_header = array();

            $invoices = $this->api_reports->getInvoicesByDate($from, $to);
            for($i = 0; $i < count($invoices); $i++){
                $output->writeln(
                    sprintf(
                        'Found [%s] Invoices',
                        count($invoices)
                    )
                );
                foreach($invoices as $invoice){

                    foreach($invoice->items as $item){

                        if(isset($invoices_total[$invoice->issue_date][$item['reference']])){
                            $invoices_total[$invoice->issue_date][$item['reference']] += (int) $item['quantity'];
                        }else{
                            if(!in_array($item['reference'], $csv_header)) $csv_header[] = $item['reference'];
                            $invoices_total[$invoice->issue_date][$item['reference']] = (int) $item['quantity'];
                        }

                    }
                }
            }

            $csv_content = $this->createCsvBody($invoices_total, $csv_header);
            array_unshift($csv_header, 'date');
            $csv_header[]='total';

            $csv_output = $this->createCsvFile($csv_content, $csv_header);

            if ($this->sendEmail(
                $email_from,
                $email_to,
                $email_subject,
                'New report ready to go!!',
                $report_name,
                $csv_output
                )
            ){
                $output->writeln('Email sent successfully Success!');
            }else{
                $output->writeln('There was an error sending the email');
            }

        }else{
            $output->writeln('Error!!');
        }
        $output->writeln('Done.');
    }
}