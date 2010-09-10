<?php
require_once dirname(__FILE__) . '/Point.class.php'; 

abstract class Creature {

    protected $_id;
    protected $_map;
    protected $_point;
    protected $_pre_point;


    function __construct($object_id, $point, $map_object)
    {
        $this->_id = $object_id;
        $this->_map = $map_object;
        $this->_point = $point;
        $this->_pre_point = null;
    }


    public function move()
    {
        //T=0の時の処理
        if($this->firstMove() === true) {
            return true;
        }

        $my_point = $this->getPoint();
        $way_count = $this->_map->countMovable($my_point);

        if($way_count === 1) {
            return $this->moveBack();
        }


        if($way_count === 2) {
            return $this->moveForward();
        }

        return $this->moveFree();
    }



    public function setPoint($point)
    {
        $this->_pre_point = clone $this->_point;
        $this->_point = $point;
        return $this;
    }



    public function getPoint()
    {
        return $this->_point;
    }



    public function getId()
    {
        return $this->_id;
    }



    public function getPlayerPoint()
    {
        return $this->_map->getPlayerPoint();
    }



    /**
     * relative point for player and enemy
     */
    public function getRelativePoint()
    {
        $player = $this->getPlayerPoint();
        $me = $this->getPoint();

        $x = $player->getX() - $me->getX();
        $y = $player->getY() - $me->getY(); 
        return new Point($x, $y);
    }



    protected function _movePoint($point) 
    {
        if ($this->_map->canMove($point)) {
            $this->setPoint($point);
            return true;
        }
        return false;
    }



    public function getPrePoint()
    {
        return $this->_pre_point;
    }



    public function moveStay()
    {
        $p = $this->getPoint();
        $this->_pre_point = new Point($p->getX(), $p->getY());
        return true;
    }


    public function moveBack()
    {
        $p = $this->getPrePoint();
        $point = new Point($p->getX(), $p->getY());
        return $this->_movePoint($point);
    }


    public function moveForward()
    {
        $pre = $this->getPrePoint();
        $now = $this->getPoint();

        $ways[] = new Point($now->getX() + 1, $now->getY());
        $ways[] = new Point($now->getX() - 1, $now->getY());
        $ways[] = new Point($now->getX(), $now->getY() + 1);
        $ways[] = new Point($now->getX(), $now->getY() - 1);


        foreach ($ways as $way) {

            if ($way->equal($pre)){
                continue;
            }

            if ($this->_movePoint($way)) {
                return true;
            }
        }

        return false;
    }


    public function moveRight()
    {
        $p = $this->getPoint();
        $point = new Point($p->getX() + 1, $p->getY());
        return $this->_movePoint($point);
    }


    public function moveLeft()
    {
        $p = $this->getPoint();
        $point = new Point($p->getX() - 1, $p->getY());
        return $this->_movePoint($point);
    }


    public function moveUp()
    {
        $p = $this->getPoint();
        $point = new Point($p->getX(), $p->getY() - 1);
        return $this->_movePoint($point);
    }



    public function moveDown()
    {
        $p = $this->getPoint();
        $point = new Point($p->getX(), $p->getY() + 1);
        return $this->_movePoint($point);
    }



    public function firstMove()
    {
        if ($this->_map->getTime() !== 0) {
            return false;
        }

        $functions = array('moveDown', 'moveLeft', 'moveUp', 'moveRight');

        foreach ($functions as $function) {
            if($this->$function() === true) {
                return true;
            }
        }

        return false;
    }

}

