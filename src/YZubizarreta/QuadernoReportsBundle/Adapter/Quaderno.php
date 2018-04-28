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

    /**
     * @throws \Exception
     */
    public function __construct(array $configuration)
    {
        $this->validateConfiguration($configuration);
        $this->_configuration = $configuration;
    }

    /**
     * @inheritdoc
     */
    public function getConfiguration():array
    {
        return $this->_configuration;
    }

    /**
     * @inheritdoc
     */
    public function connect():bool
    {
        $config = $this->getConfiguration();
        QuadernoBase::init($config['api']['private_key'], $config['api']['api_url']);
        $response = QuadernoBase::ping();
        if(!$response)
            throw new \Exception('Unable to connect');
        return $response;

    }

    public function getInvoicesByDate($from, $to = null)
    {
        $i = 1;
        $invoices = array();
        while($pageInvoice = QuadernoInvoice::find(array( 'date' => $from.','.$to, 'page' => $i))){
            $invoices = array_merge($invoices, $pageInvoice);
            $i++;
        }
        return $invoices;
    }

    /**
     * Validates the configuration format
     * @param $config
     * @throws \Exception
     */
    private function validateConfiguration($config):void
    {
        if(!array_key_exists('api', $config) ||
            count(array_diff(['private_key', 'public_key', 'api_url', 'version'], array_keys($config['api']))) > 0)
                throw new \Exception('Wrong configuration given');
    }
}