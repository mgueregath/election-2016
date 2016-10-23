<?php

use Phalcon\Mvc\Model;

class Location extends Model
{
    protected $id;
    protected $name;
    protected $hash;

    public function initialize()
    {
        $this->setSource("location");
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }
    
    public function columnMap()
    {
        return [
            'loc_id' => 'id',
            'loc_name' => 'name',
            'loc_hash' => 'hash',
        ];
    }
}
