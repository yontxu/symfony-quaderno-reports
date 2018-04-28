<?php
/**
 * Created by PhpStorm.
 * User: yzubizarreta
 * Date: 25/4/18
 * Time: 22:28
 */

namespace YZubizarreta\QuadernoReportsBundle\Adapter;


use Symfony\Component\Config\Definition\Exception\Exception;

interface ApiReportsInterface
{

    /**
     * returns current configuration
     *
     * @return array
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function getConfiguration():array;

    /**
     * returns current page and total number of pages, like [10, 100] is page 10 of 100
     *
     * @return bool
     * @throws \Exception
     */
    public function connect():bool;

    /**
     * returns current invoices in the given date range
     *
     * @param $from
     * @param $to
     * @return array []
     */
    public function getInvoicesByDate($from, $to=null);
}