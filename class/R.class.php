<?php
require_once dirname(__FILE__) . '/Creature.class.php'; 

class R extends Creature {

    public function moveFree()
    {
        $pre_p = $this->getPrePoint();
        $now_p = $this->getPoint();

        $x_v = $now_p->getX() - $pre_p->getX();
        $y_v = $now_p->getY() - $pre_p->getY();

        $moves = array(); //実行手順を入れておく

        if ($y_v === 0) {

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

        foreach ($moves as $move) {
            if ($this->$move()) {
                return true;
            }
        }

        return false;
    }

}

