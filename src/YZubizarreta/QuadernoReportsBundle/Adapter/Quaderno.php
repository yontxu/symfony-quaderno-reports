<?php
/**
 * Created by PhpStorm.
 * User: yzubizarreta
 * Date: 25/4/18
 * Time: 22:26
 */

namespace YZubizarreta\QuadernoReportsBundle\Adapter;

use QuadernoBase;
use QuadernoInvoice;

class Quaderno implements ApiReportsInterface
{

    /*
     * Quaderno configuration
     *
     * @var array
     */
    protected $_configuration;

    public function __construct(array $config)
    {
        $this->_configuration = $config;
    }

    /**
     * @inheritdoc
     */
    public function getConfiguration()
    {
        if(is_null($this->_configuration)){
            throw new Exception('Empty configuration');
        }
        return $this->_configuration;
        // TODO: Implement getConfiguration() method.
    }

    public function connect()
    {
        // TODO: Implement connect() method.
    }

    public function retrieveInvoicesByDate($from, $to = null)
    {
        // TODO: Implement retrieveInvoicesByDate() method.
    }
}