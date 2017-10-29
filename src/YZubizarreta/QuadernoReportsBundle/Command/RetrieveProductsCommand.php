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
use QuadernoBase;
use QuadernoInvoice;

class RetrieveProductsCommand extends AbstractQuadernoReportsCommand
{

    protected function configure()
    {
        $this
                ->setName('quaderno:retrieve-products')

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

        $config = $this->getContainer()->getParameter('yz_quaderno_reports.config');

        QuadernoBase::init($config['api']['private_key'], $config['api']['api_url']);
        $response = QuadernoBase::ping();
        if($response){
            $from = $input->getOption('from');
            $to = $input->getOption('to');
            $output->writeln(
                sprintf(
                    'Retrieving invoices from [%s] to [%s]',
                    $from,
                    $to
                )
            );

            $invoices_total = array();
            $csv_header = array();

            $i = 1;
            while($invoices = QuadernoInvoice::find(array( 'date' => $from.','.$to, 'page' => $i))){
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
                $i++;
            }

            $csv_content = $this->createCsvBody($invoices_total, $csv_header);
            array_unshift($csv_header, 'date');
            $csv_header[]='total';

            $csv_output = $this->createCsvFile($csv_content, $csv_header);

            if ($this->sendEmail($csv_output)) {
                $output->writeln('Email sent successfully Success!');
            } else {
                $output->writeln('There was an error sending the email');
            }

        }else{
            $output->writeln('Error!!');
        }
        $output->writeln('Done.');
    }
}