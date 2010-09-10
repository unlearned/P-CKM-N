<?php
require_once dirname(__FILE__) . '/Creature.class.php'; 

class J extends Creature {

    private $_enter_crossing_count;


    function __construct($object_id, $point, $map_object)
    {
        parent::__construct($object_id, $point, $map_object);
        $this->_enter_crossing_count = 0;
    }


    private function _countUpEnterCrossingCount()
    {
        $this->_enter_crossing_count += 1;
    }


    private function _getEnterCrossingCount()
    {
        return $this->_enter_crossing_count;
    }


    public function moveFree()
    {
        $count = $this->_getEnterCrossingCount();

        $pre_p = $this->getPrePoint();
        $now_p = $this->getPoint();

        $x_v = $now_p->getX() - $pre_p->getX();
        $y_v = $now_p->getY() - $pre_p->getY();


        $moves = array(); //実行手順を入れておく

        if ($count % 2 === 1) {

            if ($y_v === 0) {
                //behavier enemy R

                if($x_v > 0) {
                    //from left to right
                    $moves[] = 'moveDown';  //1.right
                    $moves[] = 'moveRight'; //2.front
                    $moves[] = 'moveup';    //3.left

                } else {
                    //from right to left
                    $moves[] = 'moveUp';   //1.right
                    $moves[] = 'moveLeft'; //2.front
                    $moves[] = 'moveDown'; //3.left
                }


            } elseif ($x_v === 0) {

                if($y_v > 0) {
                    //move up to down
                    $moves[] = 'moveLeft';  //1.right
                    $moves[] = 'moveDown';  //2.front
                    $moves[] = 'moveRight'; //3.left

                } else {
                    //move down to up

                    $moves[] = 'moveRight';  //1.right
                    $moves[] = 'moveUp';  //2.front
                    $moves[] = 'moveLeft'; //3.left
                }
            }

        } else {
            //behavier enemy L

            if ($y_v === 0) {

                if($x_v > 0) {
                    //from left to right
                    $moves[] = 'moveUp';    //1.left
                    $moves[] = 'moveRight'; //2.front
                    $moves[] = 'moveDown';  //3.right

                } else {
                    //from right to left
                    $moves[] = 'moveDown'; //1.left
                    $moves[] = 'moveLeft'; //2.front
                    $moves[] = 'moveUp';   //3.right
                }


            } elseif ($x_v === 0) {

                if($y_v > 0) {
                    //move up to down
                    $moves[] = 'moveRight';  //1.left
                    $moves[] = 'moveDown';   //2.front
                    $moves[] = 'moveLeft';   //3.right
                } else {
                    //move down to up
                    $moves[] = 'moveLeft';   //1.left
                    $moves[] = 'moveUp';     //2.front
                    $moves[] = 'moveRight';  //3.right
                }
            }
        }

        $this->_countUpEnterCrossingCount();

        //実行
        foreach ($moves as $move) {
            if ($this->$move()) {
                return true;
            }
        }

        return false;
    }

}

