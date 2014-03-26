<?php 
namespace Application\Mapper;

use Zend\Db\Adapter\Adapter;

abstract class AbstractMapper 
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $adapter;
    
    /**
     * @var \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway;

    public function __construct(Adapter $adapter)
    {
        $this->setAdapter($adapter);
    }
    
    public function setAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }
    
    public function getAdapter()
    {
        return $this->adapter;
    }
}