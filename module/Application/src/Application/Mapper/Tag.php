<?php
namespace Application\Mapper;

use Zend\Db\Sql;
use Zend\Db\Adapter\Adapter;
use Simukti\Utility\Slugifier;

class Tag extends AbstractMapper
{
    const TABLE_NAME = 'tag';
    
    public function fetchOneBySlug($slug)
    {
        $adapter = $this->getAdapter();
        $sql     = new Sql\Sql($adapter);
        
        $select  = $sql->select()
                       ->from(self::TABLE_NAME)
                       ->order('tag.name ' . Sql\Select::ORDER_ASCENDING);
                       
        $select->where(function(Sql\Where $where) use ($slug) {
            $where->equalTo('tag.slug', $slug);
        });
        
        $sqlString  = $sql->getSqlStringForSqlObject($select);
        $results    = $adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
        
        return $results->current();
    }
    
    public function fetchAll()
    {
        $adapter = $this->getAdapter();
        $sql     = new Sql\Sql($adapter);
        
        $select  = $sql->select()
                       ->from(self::TABLE_NAME)
                       ->join('url_tag', 'tag.id = url_tag.tag', 
                               array(), 
                               Sql\Select::JOIN_LEFT)
                       ->columns(array(
                           '*', 
                           new Sql\Expression('COUNT(url_tag.tag) AS tag_count')
                        ))
                       ->group('tag.id')
                       ->having('tag_count > 0') // hanya untuk tag yang dipakai
                       ->order('tag.name ' . Sql\Select::ORDER_ASCENDING);
                       
        $sqlString  = $sql->getSqlStringForSqlObject($select);
        $results    = $adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
        
        return $results;
    }
    
    public function fetchAllByUrlId($urlId)
    {
        $adapter = $this->getAdapter();
        $sql     = new Sql\Sql($adapter);
        
        $select  = $sql->select()
                       ->from(self::TABLE_NAME)
                       ->join('url_tag', 'tag.id = url_tag.tag', 
                               array(), 
                               Sql\Select::JOIN_LEFT)
                       ->join('url', 'url_tag.url = url.id', 
                               array(), 
                               Sql\Select::JOIN_LEFT)
                       ->columns(array('name'))
                       ->where(function(Sql\Where $where) use ($urlId) {
                            $where->equalTo('url.id', $urlId);
                        });
                        
        $sqlString  = $sql->getSqlStringForSqlObject($select);
        $results    = $adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
        
        return $results;
    }
    
    public function deleteUrlTagByUrlId($urlId)
    {
        $adapter = $this->getAdapter();
        $sql     = new Sql\Sql($adapter);
        
        $delete = $sql->delete()
                      ->from('url_tag')
                      ->where(function(Sql\Where $where) use ($urlId) {
                            $where->equalTo('url', $urlId);
                        });
        
        $adapter->query($sql->getSqlStringForSqlObject($delete), 
                        Adapter::QUERY_MODE_EXECUTE);
    }
    
    public function save($urlId, array $tags)
    {
        if(! count($tags)) {
            return;
        }
        
        $adapter = $this->getAdapter();
        $sql     = new Sql\Sql($adapter);
        
        $this->deleteUrlTagByUrlId($urlId);
        
        foreach($tags as $value) {
            $slug      = Slugifier::create($value);
            $tagExists = $this->fetchOneBySlug($slug);
            
            if(! $tagExists) {
                $insertTag = $sql->insert()
                                 ->into(self::TABLE_NAME)->values(array(
                                        'name' => trim($value),
                                        'slug' => $slug
                                    ));
                                    
                $adapter->query($sql->getSqlStringForSqlObject($insertTag), 
                                Adapter::QUERY_MODE_EXECUTE);
                
                $tagId = $adapter->getDriver()
                                 ->getLastGeneratedValue();
            } else {
                $tagId = $tagExists->id;
            }
            
            $insert  = $sql->insert()
                           ->into('url_tag')
                           ->values(array(
                                'url' => $urlId,
                                'tag' => $tagId
                            ));
                            
            $adapter->query($sql->getSqlStringForSqlObject($insert), 
                            Adapter::QUERY_MODE_EXECUTE);
        }
    }
}