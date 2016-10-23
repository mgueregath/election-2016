<?php

use Phalcon\Mvc\Model;

class Table extends Model
{
    protected $id;
    protected $descriptor;
    protected $location;
    
    public function initialize()
    {
        $this->setSource("table_l");
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDescriptor()
    {
        return $this->descriptor;
    }

    public function setDescriptor($descriptor)
    {
        $this->descriptor = $descriptor;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }
    
    public function columnMap()
    {
        return [
            'tab_id' => 'id',
            'tab_descriptor' => 'descriptor',
            'tab_location' => 'location',
        ];
    }
}
