<?php
/**
 * Created by PhpStorm.
 * User: yzubizarreta
 * Date: 29/10/17
 * Time: 18:33
 */

namespace YZubizarreta\QuadernoReportsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractQuadernoReportsCommand extends ContainerAwareCommand
{

    public function sendEmail($output)
    {
        $mail = \Swift_Message::newInstance()
                ->setSubject('test')
                ->setFrom('info@panaderiazubizarreta.com')
                ->setTo('yonzubi@gmail.com')
                ->setBody('Sales report ready to go!!')
                ->attach(
                         \Swift_Attachment::newInstance($output)
                           ->setFilename('sales_report.csv')
                           ->setContentType('application/csv')
                        );

        return $this->getContainer()->get('mailer')->send($mail);
    }

    public function createCsvBody($content, $header){
        $csv_content = array();
        foreach($content as $key => $items){
            $csv_body = array();
            foreach($header as $ref ){

                if(array_key_exists($ref, $items)){
                    $csv_body[] = $items[$ref];
                }else{
                    $csv_body[] = 0;
                }

            }
            $csv_body[] = array_sum($csv_body);
            array_unshift($csv_body, $key);
            $csv_content[] = $csv_body;
        }
        return $csv_content;
    }

    public function createCsvFile($csv_content, $csv_header){
        array_unshift($csv_content, $csv_header);

        $csv = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');

        foreach ($csv_content as $row) {
           fputcsv($csv, $row);
        }

        rewind($csv);

        return stream_get_contents($csv);
    }
}