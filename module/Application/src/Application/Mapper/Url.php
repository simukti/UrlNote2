<?php
namespace Application\Mapper;

use Zend\Db\Sql;
use Zend\Db\Adapter\Adapter;
use Simukti\Utility\Slugifier;

class Url extends AbstractMapper
{
    const TABLE_NAME = 'url';
    
    public function fetchAll()
    {
        return $this->getSelectStatement()
                    ->order('url.createdAt ' . Sql\Select::ORDER_DESCENDING);
    }
    
    public function fetchOneById($id) 
    {
        $select  = $this->getSelectStatement()
                        ->where(function(Sql\Where $where) use ($id) {
                               $where->equalTo('url.id', $id);
                          });
                          
        $adapter = $this->getAdapter();
        $sql     = new Sql\Sql($adapter);
        $result  = $adapter->query($sql->getSqlStringForSqlObject($select), 
                                   Adapter::QUERY_MODE_EXECUTE);
        
        return $result->current();
    }
    
    public function fetchAllByTagSlug($slug)
    {
        $select = $this->getSelectStatement()
                       ->order('url.createdAt ' . Sql\Select::ORDER_DESCENDING);
        
        $select->where(function (Sql\Where $where) use ($slug) {
            $where->equalTo('tag.slug', $slug);
        });
        
        return $select;
    }
    
    public function delete($urlId)
    {
        $adapter = $this->getAdapter();
        $sql     = new Sql\Sql($adapter);
        
        $delete = $sql->delete()
                      ->from(self::TABLE_NAME)
                      ->where(function(Sql\Where $where) use ($urlId) {
                            $where->equalTo('id', $urlId);
                        });
        
        $adapter->query($sql->getSqlStringForSqlObject($delete), 
                        Adapter::QUERY_MODE_EXECUTE);
    }
    
    public function save(array $data)
    {
        $adapter = $this->getAdapter();
        $sql     = new Sql\Sql($adapter);
        
        if(! array_key_exists('id', $data)) { // INSERT
            $insert = $sql->insert()
                          ->into(self::TABLE_NAME)
                          ->values(array(
                               'title' => ucwords($data['title']),
                               'slug'  => Slugifier::create($data['title']),
                               'url'   => $data['url'],
                               'note'  => $data['note'],
                          ));
            
            $adapter->query($sql->getSqlStringForSqlObject($insert), 
                            Adapter::QUERY_MODE_EXECUTE);
            
            $urlId = $this->adapter
                          ->getDriver()
                          ->getLastGeneratedValue();
            
            return $urlId;
        } else {    // UPDATE
            $urlId = $data['id'];
            unset($data['id']);
            $update  = $sql->update()
                           ->table(self::TABLE_NAME)
                           ->set(array(
                                'title' => ucwords($data['title']),
                                'slug'  => Slugifier::create($data['title']),
                                'note'  => $data['note']
                            ))
                           ->where(function(Sql\Where $where) use ($urlId) {
                                $where->equalTo('id', $urlId);
                            });
            
            $adapter->query($sql->getSqlStringForSqlObject($update), 
                            Adapter::QUERY_MODE_EXECUTE);
            
            return $urlId;
        }
    }

    /**
     * @return  \Zend\Db\Sql\Select
     */
    protected function getSelectStatement()
    {
        $adapter = $this->getAdapter();
        $sql     = new Sql\Sql($adapter);
        $select  = $sql->select()
                       ->columns(array(
                        '*',
                        'tags' => new Sql\Expression('GROUP_CONCAT(tag.slug)')
                       ))
                       ->from(self::TABLE_NAME)
                       ->join('url_tag', 'url_tag.url = url.id', 
                               array(), 
                               Sql\Select::JOIN_LEFT)
                       ->join('tag', 'url_tag.tag = tag.id', 
                               array(), 
                               Sql\Select::JOIN_LEFT)
                       ->group('url.id');
        
        return $select;
    }
}