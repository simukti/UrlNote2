<?php
namespace Application\InputFilter;

use Zend\InputFilter\InputFilter;
use Application\Mapper;
use Zend\Db\Adapter;
use Zend\Validator\Db;
use Zend\Validator\Uri as UriValidator;
use Zend\Uri;

class Url extends InputFilter
{
    /**
     * @var Adapter\Adapter
     */
    protected $dbAdapter;
    
    public function __construct(Adapter\Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    
        $this->add(array(
            'name'      => 'title',
            'required'  => true,
            'filters' => array(
                array( 'name' => 'StripTags' ),
                array( 'name' => 'StripNewLines' ),
            ),
            'validators' => array(
                array( 'name' => 'NotEmpty' ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 128,
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name'      => 'url',
            'required'  => true,
            'filters' => array(
                array( 'name' => 'StripNewLines' ),
            ),
            'validators' => array(
                array( 'name' => 'NotEmpty' ),
                array(
                    'name' => 'Uri',
                    'options' => array(
                        'uriHandler' => new Uri\Http(),
                        'messages' => array(
                            UriValidator::NOT_URI => 
                                'Url yang anda input tidak valid.'
                        )
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => 254,
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name'      => 'note',
            'required'  => false,
            'filters' => array(
                array( 'name' => 'StripTags' ),
                array( 'name' => 'StripNewLines' ),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => 1024,
                    ),
                ),
            ),
        ));
    }
    
    public function addUniqueUrlValidator()
    {
        $this->get('url')
             ->getValidatorChain()
             ->addValidator(new Db\NoRecordExists(array(
                    'adapter' => $this->dbAdapter,
                    'table'   => Mapper\Url::TABLE_NAME,
                    'field'   => 'url',
                    'messages' => array(
                        Db\RecordExists::ERROR_RECORD_FOUND => 
                            'Url ini sudah ada, tidak boleh ada duplikasi.'
                    )
                )));
        
        return $this;
    }
    
    public function addExistingUrlValidator()
    {
        $this->get('url')
             ->getValidatorChain()
             ->addValidator(new Db\RecordExists(array(
                    'adapter' => $this->dbAdapter,
                    'table'   => Mapper\Url::TABLE_NAME,
                    'field'   => 'url',
                    'messages' => array(
                        Db\RecordExists::ERROR_NO_RECORD_FOUND => 
                            'Url ini tidak boleh dirubah.'
                    )
                )));
        
        return $this;
    }
}
