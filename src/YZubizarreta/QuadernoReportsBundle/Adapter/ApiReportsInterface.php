<?php
/**
 * Created by PhpStorm.
 * User: yzubizarreta
 * Date: 25/4/18
 * Time: 22:28
 */

namespace YZubizarreta\QuadernoReportsBundle\Adapter;


interface ApiReportsInterface
{

    /**
     * returns current configuration
     *
     * @return array
     */
    public function getConfiguration();

    /**
     * returns current page and total number of pages, like [10, 100] is page 10 of 100
     *
     * @return bool
     */
    public function connect();

    /**
     * returns current invoices in the given date range
     *
     * @param $from
     * @param $to
     * @return array []
     */
    public function retrieveInvoicesByDate($from, $to=null);
}