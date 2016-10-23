<?php

use Phalcon\Mvc\Model;

class Result extends Model
{
    protected $blanks;
    protected $nulls;
    protected $ilabaca;
    protected $carrasco;
    protected $sabat;
    protected $gomez;
    protected $total;

    public function initialize()
    {
        $this->setSource("result");
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
    
    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function columnMap()
    {
        return [
            'res_blank' => 'blanks',
            'res_null' => 'nulls',
            'res_ilabaca' => 'ilabaca',
            'res_carrasco' => 'carrasco',
            'res_sabat' => 'sabat',
            'res_gomez' => 'gomez',
            'res_total' => 'total',
        ];
    }
}