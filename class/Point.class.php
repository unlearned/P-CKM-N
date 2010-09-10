<?php

class Point {

    private $_x;
    private $_y;


    function __construct($x, $y)
    {
        $this->_x = $x;
        $this->_y = $y;
    }


    public function getX()
    {
        return $this->_x;
    }


    public function getY()
    {
        return $this->_y;
    }


    public function setX($x)
    {
        $this->_x = $x;
        return $this;
    }


    public function setY($y)
    {
        $this->_y = $y;
        return $this;
    }


    public function equal($point)
    {
        if ($this->getX() === $point->getX() 
            && $this->getY() === $point->getY()) {
            return true;
        }
        return false;
    }



    function __clone()
    {
        $this->_x = $this->_x;
        $this->_y = $this->_y;
    }

}

