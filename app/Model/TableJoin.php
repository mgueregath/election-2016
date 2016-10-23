<?php

use Phalcon\Mvc\Model;

class TableJoin extends Model
{
    protected $tableOne;
    protected $tableTwo;
    
    public function initialize()
    {
        $this->setSource("table_join");
    }
    
    public function getTableOne()
    {
        return (int) $this->tableOne;
    }

    public function setTableOne($tableOne)
    {
        $this->tableOne = $tableOne;
    }

    public function getTableTwo()
    {
        return (int) $this->tableTwo;
    }

    public function setTableTwo($tableTwo)
    {
        $this->tableTwo = $tableTwo;
    }
    
    public function columnMap()
    {
        return [
            'tjo_table_one' => 'tableOne',
            'tjo_table_two' => 'tableTwo',
        ];
    }
    
}