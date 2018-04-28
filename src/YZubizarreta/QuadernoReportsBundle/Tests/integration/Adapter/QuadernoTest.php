<?php
namespace YZubizarreta\QuadernoReportsBundle\Adapter;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuadernoTest extends KernelTestCase
{
    protected $quaderno;

    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $this->quaderno = static::$kernel
            ->getContainer()
            ->get('YZubizarreta\QuadernoReportsBundle\Adapter\Quaderno');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Wrong configuration given
     */
    public function testConfigurationEmpty(){
        new Quaderno(
            array()
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Wrong configuration given
     */
    public function testConfigurationFormat(){
        new Quaderno(
            array('api' =>
                array(
                    'test' => 'test'
                )
            )
        );
    }

    public function testGetConfiguration(){
        $config = $this->quaderno->getConfiguration();
        $this->assertEquals(array('api', 'email'), array_keys($config));
        $this->assertEquals(array('private_key', 'public_key', 'api_url', 'version'), array_keys($config['api']));
        $this->assertEquals(array('from', 'to'), array_keys($config['email']));
    }

    public function testConnect(){
        $this->quaderno->connect();
        $this->assertTrue($this->quaderno->connect());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to connect
     */
    public function testWrongConnect(){
        $quaderno = new Quaderno(
            array('api' =>
                array(
                    'private_key' => 'test',
                    'public_key' => 'test',
                    'api_url' => 'test',
                    'version' => 'test'
                )
            )
        );
        $quaderno->connect();

    }

    public function testGetInvoicesByDate(){
        $this->quaderno->connect();
        $invoices = $this->quaderno->getInvoicesByDate('2018-01-01', '2018-01-01');
        $this->assertTrue(count($invoices) > 0);
    }
}