<?php

use Phalcon\Mvc\Model;

class TableVotes extends Model
{
    protected $table;
    protected $blanks;
    protected $nulls;
    protected $ilabaca;
    protected $carrasco;
    protected $sabat;
    protected $gomez;
    
    public function initialize()
    {
        $this->setSource("table_votes");
    }
    
    public function getTable()
    {
        return $this->table;
    }
    
    public function setTable($table)
    {
        $this->table = $table;
    }
    
    public function getBlanks()
    {
        return $this->blanks;
    }

    public function setBlanks($blanks)
    {
        $this->blanks = $blanks;
    }

    public function getNulls()
    {
        return $this->nulls;
    }

    public function setNulls($nulls)
    {
        $this->nulls = $nulls;
    }

    public function getIlabaca()
    {
        return $this->ilabaca;
    }

    public function setIlabaca($ilabaca)
    {
        $this->ilabaca = $ilabaca;
    }

    public function getCarrasco()
    {
        return $this->carrasco;
    }

    public function setCarrasco($carrasco)
    {
        $this->carrasco = $carrasco;
    }

    public function getSabat()
    {
        return $this->sabat;
    }

    public function setSabat($sabat)
    {
        $this->sabat = $sabat;
    }

    public function getGomez()
    {
        return $this->gomez;
    }

    public function setGomez($gomez)
    {
        $this->gomez = $gomez;
    }
    
    public function columnMap()
    {
        return [
            'tvo_table' => 'table',
            'tvo_blank' => 'blanks',
            'tvo_null' => 'nulls',
            'tvo_ilabaca' => 'ilabaca',
            'tvo_carrasco' => 'carrasco',
            'tvo_sabat' => 'sabat',
            'tvo_gomez' => 'gomez',
        ];
    }
}