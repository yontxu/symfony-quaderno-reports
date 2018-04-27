<?php
namespace YZubizarreta\QuadernoReportsBundle\Adapter;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuadernoAdapterTest extends KernelTestCase
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

    public function testGetConfiguration(){
        $config = $this->quaderno->getConfiguration();
        $this->assertEquals(array('api', 'email'), array_keys($config));
        $this->assertEquals(array('private_key', 'public_key', 'api_url', 'version'), array_keys($config['api']));
        $this->assertEquals(array('from', 'to'), array_keys($config['email']));
    }
}